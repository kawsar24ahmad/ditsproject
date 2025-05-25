<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Message;
use App\Models\Service;
use App\Models\AssignedTask;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use App\Http\Controllers\Controller;

class AssignTaskController extends Controller
{
    public function index($id)  {
        $services = Service::all();
        $serviceAssign = ServiceAssign::with('invoice', 'customer', 'employee', 'service')->findOrFail($id);

$messages = Message::where('service_assign_id', $serviceAssign->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('admin.services.assigned_tasks.index', compact('serviceAssign',  'services', 'messages'));
        // return view('admin.services.assigned_tasks.index');
    }
    public function store(Request $request, $id)
    {
        $request->validate([
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.notes' => 'nullable|string|max:1000',
        ]);

        $assign = ServiceAssign::findOrFail($id);

        foreach ($request->tasks as $task) {
            $assign->assignedTasks()->create([
                'title' => $task['title'],
                'notes' => $task['notes'] ?? null,
                'added_by' => auth()->id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tasks assigned successfully.',
            'data' => $request->all(),
        ]);
    }


}
