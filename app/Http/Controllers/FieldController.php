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
        // UNE LA FECHA Y LA HORA
        $startHour = Carbon::create(request('date'))
                            ->modify(request('start'));

        $endHour   = Carbon::create(request('date'))
                            ->modify(request('end'));

        // LISTA HORAS REPETIDAS
        $fieldsExists = Field::select('start', 'end')
            ->whereBetween('start', [$startHour, $endHour])
            ->orWhereBetween('end', [$startHour, $endHour])
            ->get();

        // LISTA HORAS REPETIDAS PERO SI PUEDO ACTUALIZAR CUANDO ES LA MISMA HORA
        // $notUpdate = DB::table('users')
        // ->join('fields', 'users.id', '=', 'fields.user_id')
        // ->where('fields.id', $request->id)
        // ->orWhereBetween('end', [$startHour, $endHour])
        // ->select('fields.id', 'fields.start', 'fields.end')
        // ->get();

        $notUpdate = Field::select('id','date','start','end')
            ->where('id', $request->id)
            ->orWhere('date', $request->date)
            ->whereBetween('start', [$startHour, $endHour])
            ->orWhereBetween('end', [$startHour, $endHour])
            ->get();

        // dd($notUpdate);

        if (count($fieldsExists) > 0 && count($notUpdate) == 1)
        {
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

            // $this->campos($new_field);

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

    // public function campos($new_field)
    // {
    //     $new_field->fill([
    //             'name'  => $new_field->name,
    //             'date'  => $new_field->date,
    //             'start' => Carbon::create($new_field->date)
    //                         ->modify($new_field->start),
    //             'end'   => Carbon::create($new_field->date)
    //                         ->modify($new_field->end),
    //             'color' => $new_field->color,
    //             'user_id' => auth()->id(),
    //         ]);

    //     return $new_field->save();
    // }
}
