<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerServiceCalendarDay;
use App\Models\CustomerServiceCalendarTask;

class EmployeeCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//     public function index()
// {
//     $user = Auth::user();

//     $tasks = $user->employeeTasks()
//         ->with([
//             'day.serviceAssign' => function($q){
//                 $q->with(['invoice','customer','service']);
//             }
//         ])
//         ->get();

//     foreach ($tasks as $key => $value) {
//         dd($value->day);
//     }



//     return view('employee.calendar', compact('tasks'));
// }


public function CustomerServiceCalendarIndex($serviceAssignId)  {
        $assignment = ServiceAssign::with([
            'service',
            'customer',
            'calendarDays.tasks.employees'
        ])->findOrFail($serviceAssignId);

        $calendarDays = $assignment->calendarDays
            ->sortBy('day_number');

             $employees = User::where('role', 'employee')->get();

        return view('employee.serviceCalender.customer_calendar', compact(
            'assignment',
            'calendarDays',
            'employees'
        ));
}
public function updateStatus(Request $request)
{
    $validData =  $request->validate([
        'task_id' => 'required|exists:customer_service_calendar_tasks,id',
        'status' => 'required|in:pending,in_progress,completed'
    ]);

    // dd($validData);

    $task = CustomerServiceCalendarTask::findOrFail($request->task_id);

    $task->update([
        'status' => $request->status
    ]);

    return back()->with('success','Status Updated');
}


public function index()
{
    $user = Auth::user();

    // Fetch days which have tasks assigned to this employee
    $days = CustomerServiceCalendarDay::with(['tasks' => function($q) use ($user) {
        $q->whereHas('employees', function($q2) use ($user) {
            $q2->where('users.id', $user->id); // Only tasks assigned to this employee
        })->with(['employees','serviceAssign.customer','serviceAssign.service','serviceAssign.invoice']);
    }])
    ->whereHas('tasks', function($q) use ($user) {
        $q->whereHas('employees', function($q2) use ($user) {
            $q2->where('users.id', $user->id);
        });
    })
    ->orderBy('day_number')
    ->get();

    return view('employee.calendar', compact('days'));
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
