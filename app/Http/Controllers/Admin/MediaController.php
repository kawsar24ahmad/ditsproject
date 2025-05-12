<?php
namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->get();
        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'type' => 'required|in:youtube,audio',
            'youtube_link' => 'nullable|url',
            'audio_file' => 'nullable|mimes:mp3,wav'
        ]);

        $data = $request->only(['title', 'type', 'youtube_link']);

        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('audio'), $filename);
            $data['audio_file'] = 'audio/' . $filename;
        }

        Media::create($data);

        return redirect()->route('admin.media.index')->with('success', 'Media Added!');
    }
    public function edit($id)
    {
        $media = Media::findOrFail($id);
        return view('admin.media.edit', compact('media'));
    }

   public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:youtube,audio',
            'youtube_link' => 'nullable|url|required_if:type,youtube',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|required_if:type,audio',
        ]);

        $media->title = $request->title;
        $media->type = $request->type;

        if ($request->type === 'youtube') {
            $media->youtube_link = $request->youtube_link;
            $media->audio_file = null; // remove audio if type changed
        }

        if ($request->type === 'audio') {
            $media->youtube_link = null;
            if ($request->hasFile('audio_file')) {
                // Delete old file if exists
                if ($media->audio_file && file_exists(public_path($media->audio_file))) {
                    unlink(public_path($media->audio_file));
                }
                $file = $request->file('audio_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('audio'), $filename);
                $media->audio_file =  'audio/' . $filename;
            }
        }

        $media->save();

        return redirect()->route('admin.media.index')->with('success', 'Media updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        // Delete associated file if type is audio
        if ($media->type === 'audio' && $media->audio_file && file_exists(public_path($media->audio_file))) {
            unlink(public_path($media->audio_file));
        }

        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully.');
    }

}
