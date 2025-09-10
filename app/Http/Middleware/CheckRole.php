<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // ถ้าเป็น visitor (ไม่ได้ login)
        if ($role === 'visitor') {
            if (!Auth::check()) {
                return $next($request);
            }
            abort(403, 'Unauthorized - Only visitors allowed');
        }

        // ตรวจสอบว่า user login แล้วหรือไม่
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin สามารถเข้าถึงทุกหน้าได้ (ยกเว้น visitor)
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        // ตรวจสอบ role สำหรับ user ทั่วไป
        if (Auth::user()->hasRole($role)) {
            return $next($request);
        }

        abort(403, 'Unauthorized - Insufficient permissions');
    }
}
