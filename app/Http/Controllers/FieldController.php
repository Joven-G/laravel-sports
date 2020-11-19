<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Field;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FieldRequest;
use App\Http\Resources\FieldResource;
use Symfony\Component\HttpFoundation\Response;

class FieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return FieldResource::collection(Field::all());
    }

    public function store(Request $request, Field $field)
    {
        $startHour = Carbon::create(request('date'))
                            ->modify(request('start'));

        $endHour   = Carbon::create(request('date'))
                            ->modify(request('end'));

        $fields = Field::select('end', 'start')
                ->whereBetween('end', [$startHour, $endHour])
                ->orWhereBetween('start', [$startHour, $endHour])
                ->get();

        if (count($fields) > 0) {

            return response()->json([
                'message' => 'La hora elegida está ocupada',
                'title'   => 'Algo Salio Mal!',
                'icon'    => 'error',
            ]); 

        } else {

            $new_calendar = Field::create([
                'name'  => request('name'),
                'date'  => request('date'),
                'start' => Carbon::create(request('date'))
                            ->modify(request('start')),
                'end'   => Carbon::create(request('date'))
                            ->modify(request('end')),
                'color' => request('color'),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data'    => new FieldResource($new_calendar),
                'message' => 'Tu reserva está hecha!',
                'title'   => 'Muy Bien!',
                'icon'    => 'success',
                'status'  => Response::HTTP_CREATED
            ]); 
        }

    }

    public function show(Field $field)
    {
        return response($field, Response::HTTP_OK);
    }

    public function update(FieldRequest $request, $id, Field $field)
    {
        $startHour = Carbon::create(request('date'))
                            ->modify(request('start'));

        $endHour   = Carbon::create(request('date'))
                            ->modify(request('end'));

        $fieldsExists = Field::select('id', 'start', 'end')
                ->whereBetween('start', [$startHour, $endHour])                         
                ->orWhereBetween('end', [$startHour, $endHour])
                ->get();

        $FieldId = Field::select('id', 'start', 'end')
                ->whereBetween('start', [$startHour, $endHour])
                ->orWhereBetween('end', [$startHour, $endHour])
                ->where('id', $request->id)->get();

        // dd($FieldId);

        // GUARDAR SI NO MODIFICA LA HORA
        $FieldsUserList = User::with('fields')
            ->where('id', auth()->id())
            ->get();

        $FieldsUser = $FieldsUserList->flatMap->fields;

        $FieldNotUpdate = $FieldsUser
                ->where('date', request('date'))
                ->where('start', $startHour)
                ->where('end', $endHour)
                ->where('id', $request->id);

        // dd($FieldNotUpdate);

        $notUpdate = DB::table('users')
            ->join('fields', 'users.id', '=', 'fields.user_id')
            ->where('fields.id', $request->id)
            ->where('date', request('date'))
            ->whereBetween('start', [$startHour, $endHour])                         
            ->orWhereBetween('end', [$startHour, $endHour])
            ->select('fields.id', 'fields.start', 'fields.end')
            ->get();

        // dd($fieldsExists);

        if (count($fieldsExists) > 0 && count($notUpdate) == 1 && count($FieldNotUpdate) > 0) {
            $new_field = Field::find($id);
            $new_field->fill([
                'name'  => $request->name,
                'date'  => $request->date,
                'start' => Carbon::create($request->date)
                            ->modify($request->start),
                'end'   => Carbon::create($request->date)
                            ->modify($request->end),
                'color' => $request->color,
                'user_id' => auth()->id(),
            ]);

            $new_field->save();

            return response()->json([
                'data'    => new FieldResource($new_field),
                'message' => 'Tu reserva fue actualizada!',
                'title'   => 'Muy Bien!',
                'icon'    => 'success',
                'status'  => Response::HTTP_CREATED
            ]); 
        }

        // VALIDA SI HAY HORAS REPETIDAS
        elseif (count($fieldsExists) > 0) {
            return response()->json([
                'message' => 'La hora elegida está ocupada',
                'title'   => 'Algo Salio Mal!',
                'icon'    => 'error',
            ]); 
        }
         else {
            $new_field = Field::find($id);
            $new_field->fill([
                'name'  => $request->name,
                'date'  => $request->date,
                'start' => Carbon::create($request->date)
                            ->modify($request->start),
                'end'   => Carbon::create($request->date)
                            ->modify($request->end),
                'color' => $request->color,
                'user_id' => auth()->id(),
            ]);

            $new_field->save();

            return response()->json([
                'data'    => new FieldResource($new_field),
                'message' => 'Tu reserva fue actualizada!',
                'title'   => 'Muy Bien!',
                'icon'    => 'success',
                'status'  => Response::HTTP_CREATED
            ]); 
        }
    }

    public function destroy(Field $field)
    {
        //
    }
}
