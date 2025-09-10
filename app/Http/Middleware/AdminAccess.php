<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        // ตรวจสอบว่า user login แล้วหรือไม่
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin สามารถเข้าถึงทุกหน้าได้
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        // User ทั่วไปไม่สามารถเข้าถึงได้
        abort(403, 'Access denied. Admin privileges required.');
    }
}
