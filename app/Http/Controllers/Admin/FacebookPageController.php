<?php

namespace App\Http\Controllers\Admin;

use App\Models\FacebookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacebookPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facebookPages = FacebookPage::latest()->paginate(10);
        // Your logic to fetch and display Facebook pages
        return view('admin.facebook_pages.index', compact('facebookPages'));
    }
    public function toggleStatus($id)
    {
        $page = FacebookPage::findOrFail($id);
        $page->status = $page->status === 'active' ? 'inactive' : 'active';
        $page->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
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
