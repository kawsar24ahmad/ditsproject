<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceTaskReport;

class ServiceTaskReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'service_assign_id' => 'required|exists:service_assigns,id',
            'employee_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'work_details' => 'required|string',
        ]);

        ServiceTaskReport::create([
            'service_assign_id' => $request->service_assign_id,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'work_details' => $request->work_details,
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
