<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Field;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\FieldResource;
use Symfony\Component\HttpFoundation\Response;

class FieldController extends Controller
{

    public function index()
    {
        return FieldResource::collection(Field::all());
    }

    public function create()
    {
        return view('fieldOne.create');
    }

    public function store(Request $request, Field $field)
    {
        $datesHour = Carbon::create(request('date'))
                            ->modify(request('start'));

        $fields = Field::select('start')
                ->where('start', $datesHour)
                ->get();
        // dd($fields->pluck('start'));

        // dd($fields->flatMap);
        // $fields->each(function($item) {
        // dd($fields);
        if (count($fields) > 0) {

            return response()->json([
                'message' => 'Está hora ya esta ocupada',
                'title' => 'Algo Salio Mal!',
                'icon' => 'error',
            ]); 

        } else {

        $new_calendar = Field::create([
            'name' => request('name'),
            'date' => request('date'),
            'start' => Carbon::create(request('date'))->modify(request('start')),
            'end' => Carbon::create(request('date'))->modify(request('end')),
        ]);

        return response()->json([
            'data' => new FieldResource($new_calendar),
            'message' => 'Tu reserva está hecha!',
            'title' => 'Muy Bien!',
            'icon' => 'success',
            'status' => Response::HTTP_CREATED
        ]); 
        }
            
            
        // });

        // foreach ($fields as $field) {
        //     dd($field->start);
        //     $datesHour = Carbon::create(request('date'))
        //                     ->modify(request('start'));
        //     // dd(Carbon::parse($field->start));
        //     $fechaHour = Carbon::parse($field->start);
        //     dd($datesHour->hour);
        //     // if ($fechaHour->hour === $datesHour->hour) {
        //     //     dd('Somos iguales');
        //     // } else {
        //     //     dd('xD');
        //     // }
        // }


        // $new_calendar = Field::create([
        //     'name' => request('name'),
        //     'date' => request('date'),
        //     'start' => Carbon::create(request('date'))->modify(request('start')),
        //     'end' => Carbon::create(request('date'))->modify(request('end')),
        // ]);

        // return response()->json([
        //     'data' => new FieldResource($new_calendar),
        //     'message' => 'Successfully added new event!',
        //     'status' => Response::HTTP_CREATED
        // ]);
    }

    public function show(Field $field)
    {
        //
    }

    public function edit(Field $field)
    {
        //
    }

    public function update(Request $request, Field $field)
    {
        //
    }

    public function destroy(Field $field)
    {
        //
    }
}
