<?php

namespace App\Http\Controllers;

use App\Http\Resources\FieldResources;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id', 'name'];

        $query = Field::query();
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);

        $fields = $query->paginate(20);
        return FieldResources::collection($fields);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.field.add");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:64'
        ]);

        $field = Field::create($validatedData);

        return response()->json([
            'message' => 'Field created successfully',
            'data' => $field,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        return view("admin.field.edit", ['field' => $field]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:fields,id',
            'name' => 'required|string|max:64',
        ]);
        $field = Field::findOrFail($validatedData['id']);
        $field->update([
            'name' => $validatedData['name'],
        ]);
        return response()->json([
            'message' => 'Field updated successfully',
            'data' => $field,
        ]);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        //
    }
}
