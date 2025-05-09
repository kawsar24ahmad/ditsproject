<?php

namespace App\Http\Controllers\Admin;

use App\Models\AssignedTask;
use App\Models\ServiceTask;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceTaskController extends Controller
{
    public function store(Request $request, $serviceId)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        ServiceTask::create([
            'service_id' => $serviceId,
            'title' => $request->title
        ]);

        return back()->with('success', 'Task added successfully.');
    }

    public function toggle($id)
    {
        $task = AssignedTask::findOrFail($id);
        if (!$task) {
            return back()->with('error', 'Task is not found');
        }
        $task->is_completed = !$task->is_completed;
        $task->completed_at =  $task->is_completed ? now() : null;
        $task->save();
        $serviceAssign = $task->serviceAssign;

         // Check if all assigned tasks under this serviceAssign are completed
        $allCompleted = $serviceAssign->assignedTasks()->where('is_completed', false)->doesntExist();

        $serviceAssign->status = $allCompleted ? 'completed' : 'in_progress';
        $serviceAssign->save();

        return back();
    }

    public function destroy($id)
    {
        $task = ServiceTask::findOrFail($id);
        $task->delete();

        return back()->with('success', 'Task deleted.');
    }
}

