<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        // Assuming only one row in site_settings table
        $settings = SiteSetting::first();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string',
            'site_url' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'bkash_account_no' => 'nullable|string',
            'bkash_type' => 'nullable|in:personal,agent',
            'nagad_account_no' => 'nullable|string',
            'nagad_type' => 'nullable|in:personal,agent',
            'bank_name' => 'nullable|string',
            'account_name' => 'nullable|string',
            'bank_account_no' => 'nullable|string',
            'bank_branch' => 'nullable|string',
        ]);

        $settings = SiteSetting::first() ?? new SiteSetting();

        $settings->site_name = $request->site_name;
        $settings->site_url = $request->site_url;
        $settings->bkash_account_no = $request->bkash_account_no;
        $settings->bkash_type = $request->bkash_type;
        $settings->nagad_account_no = $request->nagad_account_no;
        $settings->nagad_type = $request->nagad_type;
        $settings->bank_name = $request->bank_name;
        $settings->account_name = $request->account_name;
        $settings->bank_account_no = $request->bank_account_no;
        $settings->bank_branch = $request->bank_branch;

        if ($request->hasFile('logo')) {
            // Delete old photo if exists
            if ($settings->logo && file_exists(public_path($settings->logo))) {
                unlink(public_path($settings->logo));
            }

            // Store new photo
            $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('logos'), $filename);
            $settings->logo = 'logos/' . $filename;
        }

        if ($request->hasFile('favicon')) {
            // Delete old photo if exists
            if ($settings->favicon && file_exists(public_path($settings->favicon))) {
                unlink(public_path($settings->favicon));
            }

            // Store new photo
            $filename = uniqid() . '.' . $request->file('favicon')->getClientOriginalExtension();
            $request->file('favicon')->move(public_path('favicons'), $filename);
            $settings->favicon = 'favicons/' . $filename;
        }

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
