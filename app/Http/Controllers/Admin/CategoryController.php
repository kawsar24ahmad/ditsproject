<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('serial_no')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'serial_no' => 'required|integer',
        ]);

        Category::create([
            'title' => $request->title,
            'slug' => time().'-'. Str::slug($request->title),
            'serial_no' => $request->serial_no,
        ]);

        return redirect()->route('admin_categories.index')->with('success', 'Category created successfully.');
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.show', compact('category'));
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'serial_no' => 'required|integer',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'title' => $request->title,
            'slug' => time().'-'. Str::slug($request->title),
            'serial_no' => $request->serial_no,
        ]);

        return redirect()->route('admin_categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin_categories.index')->with('success', 'Category deleted successfully.');
    }
}
