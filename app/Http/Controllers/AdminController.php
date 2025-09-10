<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Performance;
use App\Models\PersonalInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $performance = Performance::orderByDesc('created_at')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => Performance::count(),
            'active' => Performance::where('status', true)->count(),
            'featured' => Performance::where('featured', true)->count(),
            'total_views' => Performance::sum('views'),
        ];
        
        return view('performance', compact('performance', 'stats'));
    }

    function doc()
    {
        return view('doc');
    }

    function stat(Request $request)
    {
        $period = $request->get('period', 30); // Default 30 days
        $startDate = Carbon::now()->subDays($period);
        $endDate = Carbon::now();

        // Basic Stats
        $stats = [
            'total_portfolios' => PersonalInfo::count(),
            'approved_portfolios' => PersonalInfo::where('status', 'approved')->count(),
            'pending_portfolios' => PersonalInfo::where('status', 'pending')->count(),
            'rejected_portfolios' => PersonalInfo::where('status', 'rejected')->count(),
            'total_users' => User::count(),
        ];

        // Faculty Stats
        $facultyStats = PersonalInfo::where('status', 'approved')
            ->selectRaw('faculty, COUNT(*) as count')
            ->groupBy('faculty')
            ->orderByDesc('count')
            ->get();

        // Major Stats
        $majorStats = PersonalInfo::where('status', 'approved')
            ->selectRaw('major, COUNT(*) as count')
            ->groupBy('major')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Chart Data
        $chartData = $this->generateChartData($startDate, $endDate);

        return view('stat', compact(
            'stats',
            'facultyStats',
            'majorStats',
            'chartData',
            'period'
        ));
    }

    /**
     * Generate chart data for portfolio trend
     */
    private function generateChartData($startDate, $endDate)
    {
        $labels = [];
        $data = [];
        
        // Generate date labels for the period
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format('d/m');
            $data[] = PersonalInfo::whereDate('created_at', $currentDate)->count();
            $currentDate->addDay();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    function create()
    {
        // Redirect to personal info create page
        return redirect()->route('personal-info.create');
    }

    function insert(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|max:100',
                'content' => 'required|max:500',
                'description' => 'nullable|max:1000',
                'category' => 'required|in:web,mobile,desktop,other',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'github_url' => 'nullable|url',
                'live_url' => 'nullable|url',
                'tags' => 'nullable|string',
            ],
            [
                'title.required'=>'กรุณาป้อนชื่อผลงานด้วยครับ',
                'title.max'=>'ชื่อผลงานห้ามเกิน 100 ตัวอักษร',
                'content.required'=>'กรุณาป้อนเนื้อหาผลงานด้วยครับ',
                'content.max'=>'เนื้อหาผลงานห้ามเกิน 500 ตัวอักษร',
                'description.max'=>'คำอธิบายห้ามเกิน 1000 ตัวอักษร',
                'category.required'=>'กรุณาเลือกหมวดหมู่',
                'image.image'=>'ไฟล์ต้องเป็นรูปภาพ',
                'image.mimes'=>'รองรับไฟล์ jpeg, png, jpg, gif เท่านั้น',
                'image.max'=>'ขนาดไฟล์ห้ามเกิน 2MB',
                'github_url.url'=>'URL GitHub ไม่ถูกต้อง',
                'live_url.url'=>'URL Live Demo ไม่ถูกต้อง',
            ]
        );

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'description' => $request->description,
            'category' => $request->category,
            'github_url' => $request->github_url,
            'live_url' => $request->live_url,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'featured' => $request->has('featured'),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('performances', 'public');
            $data['image'] = $imagePath;
        }

        Performance::create($data);
        return redirect()->route('performance')->with('success', 'เพิ่มผลงานสำเร็จ');
    }

    function delete($id){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        Performance::find($id)->delete();
        return redirect()->back();
    }

    function change($id){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $performance = Performance::findOrFail($id);
        $performance->update(['status' => !$performance->status]);
        return redirect()->back()->with('success', 'อัพเดทสถานะสำเร็จ');
    }

    function toggleFeatured($id){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $performance = Performance::findOrFail($id);
        $performance->update(['featured' => !$performance->featured]);
        return redirect()->back()->with('success', 'อัพเดทสถานะแนะนำสำเร็จ');
    }

    function bulkAction(Request $request){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $action = $request->action;
        $ids = $request->ids;

        if (!$ids || !$action) {
            return redirect()->back()->with('error', 'กรุณาเลือกผลงานและเลือกการดำเนินการ');
        }

        switch ($action) {
            case 'activate':
                Performance::whereIn('id', $ids)->update(['status' => true]);
                $message = 'เปิดใช้งานผลงานสำเร็จ';
                break;
            case 'deactivate':
                Performance::whereIn('id', $ids)->update(['status' => false]);
                $message = 'ปิดใช้งานผลงานสำเร็จ';
                break;
            case 'feature':
                Performance::whereIn('id', $ids)->update(['featured' => true]);
                $message = 'ตั้งเป็นผลงานแนะนำสำเร็จ';
                break;
            case 'unfeature':
                Performance::whereIn('id', $ids)->update(['featured' => false]);
                $message = 'ยกเลิกผลงานแนะนำสำเร็จ';
                break;
            case 'delete':
                Performance::whereIn('id', $ids)->delete();
                $message = 'ลบผลงานสำเร็จ';
                break;
            default:
                return redirect()->back()->with('error', 'การดำเนินการไม่ถูกต้อง');
        }

        return redirect()->back()->with('success', $message);
    }

    function edit($id){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $performance = Performance::find($id);
        return view('edit', compact('performance'));
    }

    function update(Request $request, $id){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $request->validate(
            [
                'title' => 'required|max:100',
                'content' => 'required|max:500',
                'description' => 'nullable|max:1000',
                'category' => 'required|in:web,mobile,desktop,other',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'github_url' => 'nullable|url',
                'live_url' => 'nullable|url',
                'tags' => 'nullable|string',
            ],
            [
                'title.required'=>'กรุณาป้อนชื่อผลงานด้วยครับ',
                'title.max'=>'ชื่อผลงานห้ามเกิน 100 ตัวอักษร',
                'content.required'=>'กรุณาป้อนเนื้อหาผลงานด้วยครับ',
                'content.max'=>'เนื้อหาผลงานห้ามเกิน 500 ตัวอักษร',
                'description.max'=>'คำอธิบายห้ามเกิน 1000 ตัวอักษร',
                'category.required'=>'กรุณาเลือกหมวดหมู่',
                'image.image'=>'ไฟล์ต้องเป็นรูปภาพ',
                'image.mimes'=>'รองรับไฟล์ jpeg, png, jpg, gif เท่านั้น',
                'image.max'=>'ขนาดไฟล์ห้ามเกิน 2MB',
                'github_url.url'=>'URL GitHub ไม่ถูกต้อง',
                'live_url.url'=>'URL Live Demo ไม่ถูกต้อง',
            ]
        );

        $performance = Performance::findOrFail($id);
        
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'description' => $request->description,
            'category' => $request->category,
            'github_url' => $request->github_url,
            'live_url' => $request->live_url,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'featured' => $request->has('featured'),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($performance->image) {
                \Storage::disk('public')->delete($performance->image);
            }
            $imagePath = $request->file('image')->store('performances', 'public');
            $data['image'] = $imagePath;
        }

        $performance->update($data);
        return redirect()->route('performance')->with('success', 'อัพเดทผลงานสำเร็จ');
    }

    function backup(){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $performances = Performance::all();
        $backupData = [
            'exported_at' => now()->toISOString(),
            'total_records' => $performances->count(),
            'performances' => $performances->toArray()
        ];

        $filename = 'portfolio_backup_' . now()->format('Y-m-d_H-i-s') . '.json';
        $filepath = storage_path('app/backups/' . $filename);

        // Create backups directory if not exists
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        file_put_contents($filepath, json_encode($backupData, JSON_PRETTY_PRINT));

        return response()->download($filepath)->deleteFileAfterSend();
    }

    function restore(Request $request){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $request->validate([
            'backup_file' => 'required|file|mimes:json'
        ]);

        try {
            $file = $request->file('backup_file');
            $content = file_get_contents($file->getRealPath());
            $data = json_decode($content, true);

            if (!$data || !isset($data['performances'])) {
                return redirect()->back()->with('error', 'ไฟล์สำรองข้อมูลไม่ถูกต้อง');
            }

            // Clear existing data
            Performance::truncate();

            // Restore data
            foreach ($data['performances'] as $performance) {
                unset($performance['id']); // Remove ID to avoid conflicts
                Performance::create($performance);
            }

            return redirect()->back()->with('success', 'กู้คืนข้อมูลสำเร็จ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการกู้คืนข้อมูล: ' . $e->getMessage());
        }
    }

    function export(){
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $performances = Performance::all();
        
        $csvData = [];
        $csvData[] = ['ID', 'Title', 'Content', 'Category', 'Status', 'Featured', 'Views', 'Created At'];

        foreach ($performances as $performance) {
            $csvData[] = [
                $performance->id,
                $performance->title,
                $performance->content,
                $performance->category,
                $performance->status ? 'Active' : 'Inactive',
                $performance->featured ? 'Yes' : 'No',
                $performance->views,
                $performance->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'portfolio_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/exports/' . $filename);

        // Create exports directory if not exists
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $file = fopen($filepath, 'w');
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend();
    }

    /**
     * Show user management page
     */
    function users()
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Show user password reset form
     */
    function showPasswordResetForm($userId)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $user = User::findOrFail($userId);
        
        return view('admin.password-reset', compact('user'));
    }

    /**
     * Reset user password
     */
    function resetUserPassword(Request $request, $userId)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required|min:8',
        ], [
            'new_password.required' => 'กรุณาป้อนรหัสผ่านใหม่',
            'new_password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'new_password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            'new_password_confirmation.required' => 'กรุณายืนยันรหัสผ่าน',
            'new_password_confirmation.min' => 'รหัสผ่านยืนยันต้องมีอย่างน้อย 8 ตัวอักษร',
        ]);
        
        $user = User::findOrFail($userId);
        
        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        // Log the action
        \Log::info('Admin ' . auth()->user()->name . ' reset password for user ' . $user->name . ' (ID: ' . $user->id . ')');
        
        return redirect()->route('admin.users')->with('success', 'รีเซ็ตรหัสผ่านสำหรับ ' . $user->name . ' เรียบร้อยแล้ว');
    }

    /**
     * Show user edit form
     */
    function editUser($userId)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $user = User::findOrFail($userId);
        
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update user information
     */
    function updateUser(Request $request, $userId)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'role' => 'required|in:user,admin',
        ], [
            'name.required' => 'กรุณาป้อนชื่อ',
            'name.max' => 'ชื่อห้ามเกิน 255 ตัวอักษร',
            'email.required' => 'กรุณาป้อนอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้มีผู้ใช้แล้ว',
            'role.required' => 'กรุณาเลือกบทบาท',
            'role.in' => 'บทบาทไม่ถูกต้อง',
        ]);
        
        $user = User::findOrFail($userId);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.users')->with('success', 'อัปเดตข้อมูลผู้ใช้ ' . $user->name . ' เรียบร้อยแล้ว');
    }

    /**
     * Delete user
     */
    function deleteUser($userId)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $user = User::findOrFail($userId);
        
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }
        
        $userName = $user->name;
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'ลบผู้ใช้ ' . $userName . ' เรียบร้อยแล้ว');
    }
}
