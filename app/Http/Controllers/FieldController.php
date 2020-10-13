<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Field;
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

    public function store(Request $request)
    {
        $new_calendar = Field::create([
            'name' => request('name'),
            'date' => request('date'),
            'start' => Carbon::parse($request->get('start'))->format('H:i'),
            'end' => request('end'),
        ]);

        return response()->json([
            'data' => new FieldResource($new_calendar),
            'message' => 'Successfully added new event!',
            'status' => Response::HTTP_CREATED
        ]);
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
