<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category', 'tasks')->latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'icon' => 'nullable',
            'is_active' => 'boolean',
            'type' => 'nullable|string',
        ]);

        // Slug handling
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $validated['slug'] = $slug;

        // Thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Store new photo
            $filename =  uniqid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('thumbnails'), $filename);
            $validated['thumbnail'] = 'thumbnails/' . $filename;
        }

        // Default active status if not sent
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $service = Service::create($validated);

        return redirect()->route('admin.tasks.create', $service->id)->with('success', 'Service created successfully.');
    }


    // public function show(string $id)
    // {
    //     $service = Service::findOrFail($id);

    //     switch ($service->type) {
    //         case 'view_only':
    //             return view($service->view_path, compact('service'));
    //         case 'form':
    //             return view( 'user.services.form', compact('service'));
    //         case 'external_link':
    //             return redirect()->away($service->external_link);
    //         default:
    //             abort(404);
    //     }
    // }

    public function edit(string $id)
    {
        $categories = Category::all();
        $service = Service::with('category')->findOrFail($id);
        return view('admin.services.edit', compact('service', 'categories'));
    }


    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'icon' => 'nullable',
            'is_active' => 'boolean',
            'type' => 'nullable|string'
        ]);

        // Unique slug handling
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Service::where('slug', $slug)->where('id', '!=', $service->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $validated['slug'] = $slug;

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($service->thumbnail && file_exists(public_path($service->thumbnail))) {
                unlink(public_path($service->thumbnail));
            }
            $filename = uniqid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('thumbnails'), $filename);
            $validated['thumbnail'] = 'thumbnails/' . $filename;
        }

        // Default active status
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Save updated service
        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }



    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        // delete old
        if ($service->thumbnail && file_exists($service->thumbnail)) {
            unlink(public_path($service->thumbnail));
        }
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
