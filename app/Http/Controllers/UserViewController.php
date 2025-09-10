<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class UserViewController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        // ดึงรายชื่อ User ทั้งหมด
        $users = User::where('role', 'user')
            ->withCount(['personalInfos as total_projects' => function($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['personalInfos as pending_projects' => function($query) {
                $query->where('status', 'pending');
            }])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.user-view.index', compact('users'));
    }

    public function show($userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user = User::findOrFail($userId);
        
        // ดึงข้อมูลผลงานของ User คนนี้
        $userProjects = PersonalInfo::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(12);

        // สถิติของ User
        $totalProjects = PersonalInfo::where('user_id', $userId)->count();
        $approvedProjects = PersonalInfo::where('user_id', $userId)->where('status', 'approved')->count();
        $pendingProjects = PersonalInfo::where('user_id', $userId)->where('status', 'pending')->count();
        $rejectedProjects = PersonalInfo::where('user_id', $userId)->where('status', 'rejected')->count();

        return view('admin.user-view.show', compact(
            'user', 
            'userProjects', 
            'totalProjects', 
            'approvedProjects', 
            'pendingProjects', 
            'rejectedProjects'
        ));
    }

    public function dashboard($userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user = User::findOrFail($userId);
        
        // ดึงข้อมูลเหมือนหน้า Dashboard ของ User
        $userProjects = PersonalInfo::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $totalProjects = $userProjects->count();
        $approvedProjects = $userProjects->where('status', 'approved')->count();
        $pendingProjects = $userProjects->where('status', 'pending')->count();
        $rejectedProjects = $userProjects->where('status', 'rejected')->count();

        return view('admin.user-view.dashboard', compact(
            'user', 
            'userProjects', 
            'totalProjects', 
            'approvedProjects', 
            'pendingProjects', 
            'rejectedProjects'
        ));
    }

    public function edit($userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user = User::findOrFail($userId);
        return view('admin.user-view.edit', compact('user'));
    }

    public function update(Request $request, $userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,user'],
        ]);

        $user->fill($request->only(['name', 'email', 'role']));
        $user->save();

        return redirect()->route('user-view.show', $user->id)
            ->with('success', 'ข้อมูล User ถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy($userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user = User::findOrFail($userId);
        
        // ตรวจสอบว่าไม่ใช่ Admin คนเดียวในระบบ
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'ไม่สามารถลบ Admin คนสุดท้ายในระบบได้');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('user-view.index')
            ->with('success', 'User "' . $userName . '" ถูกลบเรียบร้อยแล้ว');
    }
}
