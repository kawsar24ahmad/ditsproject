<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Check if user already has a password
        $rules = [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];
    
        if ($user->password) {
            // If password exists, require current password validation
            $rules['current_password'] = ['required', 'current_password'];
        }
    
        $validated = $request->validateWithBag('updatePassword', $rules);
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);


        return back()->with('status', 'password-updated');
    }
}
