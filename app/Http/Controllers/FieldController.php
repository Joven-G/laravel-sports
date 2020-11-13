<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Field;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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

        $fieldsExists = Field::select('end', 'start')
                ->whereBetween('end', [$startHour, $endHour])
                ->orWhereBetween('start', [$startHour, $endHour])
                ->get();

        // dd($field->isDirty('start'));

        if (count($fieldsExists) > 0) {

            if ($field->isDirty('start') || $field->isDirty('end')) {
                dd('los guarde!');
            }

            return response()->json([
                'message' => 'La hora elegida está ocupada',
                'title'   => 'Algo Salio Mal!',
                'icon'    => 'error',
            ]); 

        } else {

            $new_field = Field::find($id);

            // $new_field->name = request('name');
            // $new_field->date = request('date');
            // $new_field->start = Carbon::create(request('date'))->modify(request('start'));
            // $new_field->end = Carbon::create(request('date'))->modify(request('end'));
            // $new_field->color = request('color');
            // $new_field->user_id = auth()->id();

            // $new_field->save();

            // dd($new_field);

            // $new_field->fill([
            //     'name'  => request('name'),
            //     'date'  => request('date'),
            //     'start' => Carbon::create(request('date'))
            //                 ->modify(request('start')),
            //     'end'   => Carbon::create(request('date'))
            //                 ->modify(request('end')),
            //     'color' => request('color'),
            //     'user_id' => auth()->id(),
            // ]);

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

            // dd($field);

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
