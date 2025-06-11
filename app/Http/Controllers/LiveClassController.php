<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use Illuminate\Http\Request;

class LiveClassController extends Controller
{
    // Show all live classes
    public function index()
    {
        $liveClasses = LiveClass::latest()->get();
        return view('admin.live_class.index', compact('liveClasses'));
    }

    // Show form to create a live class
    public function create()
    {
        return view('admin.live_class.create');
    }

    // Store live class
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_url' => 'required|url',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        LiveClass::create($validated);

        return redirect()->route('live_class.index')->with('success', 'Live class created successfully.');
    }

    // Show form to edit a live class
    public function edit($id)
    {
        $liveClass = LiveClass::findOrFail($id);
        return view('live_class.edit', compact('liveClass'));
    }


    // Update live class
    public function update(Request $request, $id)
    {
        $liveClass = LiveClass::findOrFail($id);
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_url' => 'required|url',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        $liveClass->update($validated);

        return redirect()->route('live_class.index')->with('success', 'Live class updated successfully.');
    }

    // Delete a live class
    public function destroy($id)
    {
        $liveClass = LiveClass::findOrFail($id);
        $liveClass->delete();
        return redirect()->back()->with('success', 'Live class deleted successfully.');
    }
}
