<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use Illuminate\Http\Request;
use App\Models\RecordedClass;

class ClassController extends Controller
{
    public function liveClass()  {
        $liveClasses = LiveClass::latest()->get();
        return view('user.live_class.index', compact('liveClasses'));
    }
    public function createLiveClass()  {
        return view('user.live_class.index');
    }
    public function recordedClass()  {
        $classes = RecordedClass::all();
        return view('user.recorded_class.index', compact('classes'));
    }
    public function adminRecordedClass()  {
        $classes = RecordedClass::all();
        return view('admin.recorded_class.index', compact('classes'));
    }
    public function createRecordedClass()  {
        return view('admin.recorded_class.create');
    }
    public function editRecordedClass(RecordedClass $recordedClass)  {
        return view('admin.recorded_class.edit', compact('recordedClass'));
    }
    public function storeRecordedClass(Request $request)  {
      // Validate the form inputs
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'youtube_link' => 'required|url',
        ]);

        // Store the new recorded class
        RecordedClass::create([
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'youtube_link' => $validated['youtube_link'],
        ]);

        // Redirect or respond
        return redirect()->back()->with('success', 'Recorded class added successfully.');
    }
   public function recordedClassUpdate(Request $request, RecordedClass $recordedClass)
    {
        // Validate the form inputs
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'youtube_link' => 'required|url',
        ]);

        // Update the existing recorded class
        $recordedClass->update([
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'youtube_link' => $validated['youtube_link'],
        ]);

        // Redirect or respond
        return redirect()->route('admin.recordedClass')->with('success', 'Recorded class updated successfully.');
    }

    public function recordedClassDestroy(RecordedClass $recordedClass)  {
        $recordedClass->delete();
        return redirect()->back()->with('success', 'Recorded class deleted successfully.');
    }
}
