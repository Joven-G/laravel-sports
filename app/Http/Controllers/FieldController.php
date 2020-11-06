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
        //
    }

    public function update(Request $request, Field $field)
    {
        $field->update($request->all());
        return response()->json([
            'data' => new FieldResource($field),
            'message' => 'Successfully updated event!',
            'status' => Response::HTTP_ACCEPTED
        ]);
    }

    public function destroy(Field $field)
    {
        //
    }
}
