<?php

namespace App\Http\Controllers;

use App\Models\ServiceCalendarDay;
use App\Models\ServiceCalendarTask;
use Illuminate\Http\Request;

class ServiceCalendarTaskController extends Controller
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
        // dd($request->all());
        $request->validate([
            'day_number' => 'required',
            'title' => 'required|string|max:255'
        ]);

        $day = ServiceCalendarDay::where([
            'day_number'    =>  $request->day_number,
            'service_id'    =>  $request->service_id
        ])->firstOrFail();

        // dd($day);

        $task = ServiceCalendarTask::create([
            'service_calendar_day_id' => $day->id,
            'title' => $request->title,
            'status' => 'pending'
        ]);

        if($request->employee_ids){
            $task->employees()->sync($request->employee_ids);
        }

        return back()->with('success','Task Added');
    }
    public function assignTask(Request $request)
    {
       $validatedData =  $request->validate([
            'task_ids' => 'required|array',
            'employee_ids' => 'required|array'
        ]);
        // dd($validatedData);

        foreach($request->task_ids as $taskId){
            $task = ServiceCalendarTask::find($taskId);
            $task->employees()->sync($request->employee_ids);
        }

        return back()->with('success','Tasks Assigned');
    }

    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'task_ids' => 'required|array',
            'status' => 'required|in:pending,in_progress,completed',
        ]);
        // dd($validatedData);

        foreach ($request->task_ids as $taskId) {
            $task = ServiceCalendarTask::find($taskId);
            if($task){
                $task->update(['status' => $request->status]);
            }
        }

        return back()->with('success', 'Task status updated successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(ServiceCalendarTask $serviceCalendarTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceCalendarTask $serviceCalendarTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCalendarTask $serviceCalendarTask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCalendarTask $serviceCalendarTask)
    {
        //
    }
}
