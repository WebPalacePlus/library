<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {   
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request);
        }
        return redirect('/login')->withErrors(['access' => 'دسترسی غیرمجاز.']);
    }
}
