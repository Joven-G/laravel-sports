<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use App\Http\Resources\FieldResource;
use Symfony\Component\HttpFoundation\Response;

class FieldController extends Controller
{

    public function index()
    {
        return view('fieldOne.index');
    }

    public function create()
    {
        return view('fieldOne.create');
    }

    public function store(Request $request)
    {
        $new_calendar = Calendar::create([
            'name' => request('name'),
            'date' => request('date'),
            'start' => request('start'),
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
