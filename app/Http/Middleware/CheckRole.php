<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        if ( !$user ) {
           return redirect()->route('login');
        }
        if (!in_array($user->role, $roles)) {
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'user':
                case 'customer':
                    return redirect()->route('user.dashboard');
                case 'employee':
                    return redirect()->route('employee.dashboard');
                default:
                    return  redirect()->route($user->role .'.dashboard'); // Forbidden for unknown roles
            }
        }
        return $next($request);
    }
}
