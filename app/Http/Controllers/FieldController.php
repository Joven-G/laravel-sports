<?php

namespace App\Http\Controllers;

use DateInterval;
use Carbon\Carbon;
use App\Models\Field;
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

    public function store(FieldRequest $request, Field $onefield)
    {
        $startHour = Carbon::create(request('date'))
                            ->modify(request('start'));

        $endHour   = Carbon::create(request('date'))
                            ->modify(request('end'));

        $hours    = Carbon::parse(request('end'))
                        ->diff(Carbon::parse(request('start')))
                        ->format('%h:%i');

        // dd($hours);

        $fields = Field::select('start', 'end')
            ->whereBetween('start', [$startHour, $endHour])
            ->orWhereBetween('end', [$startHour, $endHour])
            ->orWhere(function($query) {
                $startHour = Carbon::create(request('date'))
                                ->modify(request('start'));

                $endHour   = Carbon::create(request('date'))
                                ->modify(request('end'));

                $query->where('start', '<', $startHour)
                      ->where('end',   '>', $endHour);
            })
            ->get();

        // dd($fields);

        // $fields = Field::select('start', 'end')
        //     ->whereBetween('start', [$startHour, $endHour])
        //     ->orWhereBetween('end', [$startHour, $endHour])
        //     ->get();

        // $betweenHours = Field::select('id', 'start', 'end')
        //     ->where('start', '<', $startHour)
        //     ->where('end',   '>', $endHour)
        //     ->get();

        if (count($fields) > 0) {

            return response()->json([
                'message' => 'La hora elegida está ocupada',
                'title'   => 'Algo Salio Mal!',
                'icon'    => 'error',
            ]); 

        } else {
            $new_calendar = Field::create([
                'name'  => $request->name,
                'date'  => $request->date,
                'start' => Carbon::create($request->date)
                            ->modify($request->start),
                'end'   => Carbon::create($request->date)
                            ->modify($request->end),
                'color' => $request->color,
                'hour' => $hours,
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

    public function show(Field $onefield)
    {
        return response($onefield, Response::HTTP_OK);
    }

    public function update(FieldRequest $request, Field $onefield)
    {
        // UNE LA FECHA Y LA HORA
        $startHour = Carbon::create(request('date'))
                            ->modify(request('start'));

        $endHour   = Carbon::create(request('date'))
                            ->modify(request('end'));

        $testing = Field::select('id','date','start','end')
            ->where('id', $request->id)
            ->orWhere('date', $request->date)
            ->whereBetween('start', [$startHour, $endHour])
            ->orWhereBetween('end', [$startHour, $endHour])
            ->orWhere(function($query) {
                $startHour = Carbon::create(request('date'))
                                ->modify(request('start'));

                $endHour   = Carbon::create(request('date'))
                                ->modify(request('end'));

                $query->whereBetween('start',[$startHour,$endHour])
                      ->orWhereBetween('end',[$startHour,$endHour]);
            })
            ->get();

        // dd($testing);

        // LISTA HORAS REPETIDAS
        // $fieldsExists = Field::select('start', 'end')
        //     ->whereBetween('start', [$startHour, $endHour])
        //     ->orWhereBetween('end', [$startHour, $endHour])
        //     ->get();

        // $notUpdate = Field::select('id','date','start','end')
        //     ->where('id', $request->id)
        //     ->orWhere('date', $request->date)
        //     ->whereBetween('start', [$startHour, $endHour])
        //     ->orWhereBetween('end', [$startHour, $endHour])
        //     ->get();    

        if (count($testing) == 1)
        {
            $this->campos($request, $onefield);

            $onefield->save();

            return response()->json([
                'data'    => new FieldResource($onefield),
                'message' => 'Tu reserva fue actualizada!',
                'title'   => 'Muy Bien!',
                'icon'    => 'success',
                'status'  => Response::HTTP_CREATED
            ]); 
        }

        // VALIDA SI HAY HORAS REPETIDAS
        else {
            return response()->json([
                'message' => 'La hora elegida está ocupada',
                'title'   => 'Algo Salio Mal!',
                'icon'    => 'error',
            ]); 
        }
        //  else {

        //     $this->campos($request, $onefield);

        //     $onefield->save();

        //     return response()->json([
        //         'data'    => new FieldResource($onefield),
        //         'message' => 'Tu reserva fue actualizada!',
        //         'title'   => 'Muy Bien!',
        //         'icon'    => 'success',
        //         'status'  => Response::HTTP_CREATED
        //     ]); 
        // }
    }

    public function destroy(Field $onefield)
    {        
        $onefield->delete();
        return response('Event removed successfully!', Response::HTTP_NO_CONTENT);
    }

    public function campos($request, $onefield)
    {
        return $onefield->fill([
                'name'  => $request->name,
                'date'  => $request->date,
                'start' => Carbon::create($request->date)
                            ->modify($request->start),
                'end'   => Carbon::create($request->date)
                            ->modify($request->end),
                'color' => $request->color,
                'user_id' => auth()->id(),
            ]);
    }
}
