<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInfo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // ถ้าเป็น Admin ให้แสดงผลงานทั้งหมด
        if ($user->isAdmin()) {
            $userProjects = PersonalInfo::orderByDesc('created_at')->paginate(10);
            $totalProjects = PersonalInfo::count();
            $approvedProjects = PersonalInfo::where('status', 'approved')->count();
            $pendingProjects = PersonalInfo::where('status', 'pending')->count();
            $rejectedProjects = PersonalInfo::where('status', 'rejected')->count();
        } else {
            // ถ้าเป็น User ปกติ ให้แสดงเฉพาะผลงานของตัวเอง
            $userProjects = PersonalInfo::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->paginate(10);
            $totalProjects = PersonalInfo::where('user_id', $user->id)->count();
            $approvedProjects = PersonalInfo::where('user_id', $user->id)
                ->where('status', 'approved')->count();
            $pendingProjects = PersonalInfo::where('user_id', $user->id)
                ->where('status', 'pending')->count();
            $rejectedProjects = PersonalInfo::where('user_id', $user->id)
                ->where('status', 'rejected')->count();
        }

        return view('home', compact(
            'userProjects',
            'totalProjects',
            'approvedProjects',
            'pendingProjects',
            'rejectedProjects'
        ));
    }
}
