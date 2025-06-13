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
   public function storeRecordedClass(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4',
        ]);

        // Ensure one of them is provided
        if (!$request->youtube_link && !$request->hasFile('video_file')) {
            return back()->with(['youtube_link' => 'You must provide either a YouTube link or upload a video file.'])->withInput();
        }

        if ($request->youtube_link && $request->hasFile('video_file')) {
            return back()->withErrors(['youtube_link' => 'Please provide only one: YouTube link OR video file, not both.'])->withInput();
        }

        $recordedClass = new RecordedClass();
        $recordedClass->title = $request->title;
        $recordedClass->description = $request->description;

        if ($request->youtube_link) {
            $recordedClass->youtube_link = $request->youtube_link;
        } elseif ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('videos');

            // ফোল্ডার না থাকলে তৈরি করো
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            $recordedClass->video_path = 'videos/' . $filename;

        }

        $recordedClass->save();

        return redirect()->back()->with('success', 'Recorded class saved successfully!');
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
