<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use App\Models\CustomerServiceCalendarDay;
use App\Models\CustomerServiceCalendarTask;

class CustomerServiceCalendarDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function storeTask(Request $request)
    {
       $validData =  $request->validate([
            'day_id' => 'required|exists:customer_service_calendar_days,id',
            'title' => 'required|string|max:255',
            'status' => 'nullable|in:pending,in_progress,completed',
            'employee_ids' => 'nullable|array'
        ]);
        // dd($validData);


        // Create Task
        $task = CustomerServiceCalendarTask::create([
            'calendar_day_id' => $request->day_id,
            'title' => $request->title,
            'status' => $request->status,
        ]);

        // Attach Employees
        if($request->employee_ids){
            $task->employees()->sync($request->employee_ids);
        }

        return back()->with('success','Task added successfully');
    }

    public function index($serviceAssignId)
    {
        $assignment = ServiceAssign::with([
            'service',
            'customer',
            'calendarDays.tasks.employees'
        ])->findOrFail($serviceAssignId);

        $calendarDays = $assignment->calendarDays
            ->sortBy('day_number');

        $employees = User::where('role', 'employee')->get();

        return view('serviceCalender.customer_calendar', compact(
            'assignment',
            'calendarDays',
            'employees'
        ));
    }


    public function userCalender($serviceAssignId)
    {
        $assignment = ServiceAssign::with([
            'service',
            'customer',
            'calendarDays.tasks.employees'
        ])->findOrFail($serviceAssignId);

        $calendarDays = $assignment->calendarDays
            ->sortBy('day_number');

             $employees = User::where('role', 'employee')->get();

        return view('customer.serviceCalender.customer_calendar', compact(
            'assignment',
            'calendarDays',
            'employees'
        ));
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
    public function show(CustomerServiceCalendarDay $customerServiceCalendarDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerServiceCalendarDay $customerServiceCalendarDay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $task = CustomerServiceCalendarTask::findOrFail($request->task_id);
        // dd($task);
        $task->update([
           'title' =>  $request->title,
           'status' =>  $request->status,
        ]);
        return back()->with('success', 'Updated');
    }
    public function updateMultipleStatus(Request $request)
    {
       $task_ids =  explode(',', $request->task_ids);
        foreach ($task_ids as $id) {
            $task = CustomerServiceCalendarTask::findOrFail($id);
            $task->update([
                'status' => $request->status,
            ]);
        }

        return back()->with('success', 'Updated');
    }
    public function assignMultiple(Request $request)
    {
        // dd($request->all());
        foreach ($request->task_ids as $id) {
            $task = CustomerServiceCalendarTask::findOrFail($id);
            $task->employees()->sync($request->employee_ids);
        }

        return back()->with('success', 'Task Assigned');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerServiceCalendarDay $customerServiceCalendarDay)
    {
        //
    }
}
