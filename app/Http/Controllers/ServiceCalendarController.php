<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCalendarDay;
use App\Models\ServiceCalendarTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceCalendarController extends Controller
{







    /**
     * Display a listing of the resource.
     */
    public function updateTask(Request $request)  {
        $task = ServiceCalendarTask::findOrFail($request->task_id);

        $task->update([
            'title' => $request->title,
            'status' => $request->status
        ]);



        return back()->with('success','Task updated successfully');
    }


    public function index($id)
    {
        $service = Service::findOrFail($id);
        $calendars = ServiceCalendarDay::where('service_id', 2)->get();
        // dd($calendars);
        return view('serviceCalender.index', compact('calendars','service'));
    }
    public function all($id)
    {
        $service = Service::findOrFail($id);
        $calendars = ServiceCalendarDay::where('service_id', $id)->get();
        $employees = User::where('role', 'employee')->get();
        // dd($calendars);
        return view('serviceCalender.index', compact('calendars','employees', 'service'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        $employees = User::all();
        return view('serviceCalender.create', compact('services', 'employees'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'days' => 'required|array|min:1',
            'days.*.tasks' => 'required|array|min:1',
            'days.*.tasks.*.title' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Remove old days and tasks for this service first
            $oldDays = ServiceCalendarDay::where('service_id', $validated['service_id'])->get();

            foreach ($oldDays as $day) {
                // Delete related tasks
                $day->tasks()->delete();
            }

            // Delete the old days
            ServiceCalendarDay::where('service_id', $validated['service_id'])->delete();

            // 2️⃣ Create new days and tasks
            foreach ($validated['days'] as $dayNumber => $dayData) {
                $day = ServiceCalendarDay::create([
                    'service_id' => $validated['service_id'],
                    'day_number' => (int) $dayNumber,
                    'sort_order' => (int) $dayNumber,
                ]);

                foreach ($dayData['tasks'] as $taskIndex => $taskData) {
                    ServiceCalendarTask::create([
                        'service_calendar_day_id' => $day->id,
                        'title' => $taskData['title'],
                        'status' => 'pending',
                        'sort_order' => $taskIndex + 1,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Service calendar updated successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update service calendar: ' . $e->getMessage());
        }
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
