<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceAssign;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }
    public function employeeIndex()
    {
        $users = User::where('role', 'employee')->paginate(10);
        return view('admin.employee.index', compact('users'));
    }

    public function employeeAssignments(Request $request)
    {
        $query = ServiceAssign::query();

        if ($request->has('user_id')) {
            $query->where('employee_id', $request->user_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->get();

        return view('admin.employee.assignment', compact('assignments'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable',
            'password' => 'required|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'   => 'required|in:active,inactive',
            'role'     => 'required|in:user,customer,employee',
            'fb_id_link' => 'nullable|url',
            'fb_page_link' => 'nullable|url',
            'starting_followers' => 'nullable',
        ]);

        // Handle avatar upload if exists

        if ($request->hasFile('avatar')) {
            // Store new photo
            $filename =  uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('avatars'), $filename);
            $avatar = 'avatars/' . $filename;
        }




        // Create the user
        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->password = bcrypt($request->password);
        $user->avatar   = $avatar ?? null;
        $user->status   = $request->status;
        $user->role   = $request->role;
        $user->fb_id_link = $request->fb_id_link;
        $user->fb_page_link = $request->fb_page_link;
        $user->starting_followers = $request->starting_followers;
        $user->save();

        // ✅ Return response based on request type
        if ($request->ajax()) {
            return response()->json([
                'all' => $request->all(),
                'success' => true,
                'message' => 'User created successfully.',
                'user'    => $user
            ]);
        } else {
            return redirect()->route('admin_users.index')->with('success', 'User created successfully.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user'    => $user,
            'message' => 'User retrieved successfully.'
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|min:6',
            'status' => 'required|in:active,inactive',
            'role'     =>  'nullable|in:user,customer,admin,employee',
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Update user details
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->status = $validated['status'];
        $user->role   =  $validated['role'];

        // Only update the password if it's not empty
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Save the user
        $user->save();

        // Redirect back with a success message
        return redirect()->route('admin_users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar && file_exists($user->avatar)) {
            unlink(public_path($user->avatar));
        }
        $user->delete();

        return redirect()->route('admin_users.index')->with('success', 'User deleted successfully.');
    }

}
