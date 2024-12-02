<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResources;
use App\Models\logs;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id'];
        $count = $request->get('count',25);

        $query = logs::with(['user','book']);
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);
        $logs = $query->paginate($count);
        return LogResources::collection($logs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(logs $logs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(logs $logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, logs $logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(logs $logs)
    {
        //
    }
}
