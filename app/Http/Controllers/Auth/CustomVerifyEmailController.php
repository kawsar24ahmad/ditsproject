<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class CustomVerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid or expired verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('message', 'Email already verified.');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        // Optional: log the user in automatically
        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('message', 'Email verified successfully.');
    }
}
