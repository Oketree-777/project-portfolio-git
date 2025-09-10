<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserViewController;
use App\Http\Controllers\PortfolioController;

// หน้าแสดง Portfolio (ทุกคนสามารถเข้าถึงได้)
Route::get('/', function() {
    if (Auth::check()) {
        return app(\App\Http\Controllers\PerformanceController::class)->index();
    } else {
        return app(\App\Http\Controllers\GuestController::class)->landing();
    }
})->name('home');
Route::get('/detail/{id}',[PerformanceController::class, 'detail'])->name('detail');
Route::get('/category/{category}',[PerformanceController::class, 'category'])->name('category');
Route::get('/faculty/{faculty}',[PerformanceController::class, 'faculty'])->name('faculty');
Route::get('/search',[PerformanceController::class, 'search'])->name('search');

// Portfolio Routes (ทุกคนสามารถเข้าถึงได้)
Route::get('/portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
Route::get('/portfolios/faculty/{faculty}', [PortfolioController::class, 'byFaculty'])->name('portfolios.by-faculty');
Route::get('/portfolios/major/{major}', [PortfolioController::class, 'byMajor'])->name('portfolios.by-major');
Route::get('/portfolios/search', [PortfolioController::class, 'search'])->name('portfolios.search');



// หน้าสำหรับผู้เข้าชมทั่วไป (ไม่ต้องล็อกอิน)
Route::get('/guest', [App\Http\Controllers\GuestController::class, 'landing'])->name('guest.landing');
Route::get('/guest/stat', [App\Http\Controllers\GuestController::class, 'stat'])->name('guest.stat');

// Documents Approval Routes (Admin เท่านั้น) - ต้องอยู่ก่อน route ที่มี parameter
Route::middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/documents/pending', [DocumentController::class, 'pending'])->name('documents.pending');
    Route::get('/documents/approved', [DocumentController::class, 'approved'])->name('documents.approved');
    Route::get('/documents/rejected', [DocumentController::class, 'rejected'])->name('documents.rejected');
    
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::post('/documents/{document}/cancel-approval', [DocumentController::class, 'cancelApproval'])->name('documents.cancel-approval');
});

// Documents (Admin และ User สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
});

// Profile Routes (User และ Admin สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// เพิ่มโปรเจค (Admin และ User สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/insert', [AdminController::class, 'insert'])->name('insert');
    
    // คู่มือการใช้งานและสถิติ (Admin และ User เท่านั้น)
    Route::get('/doc', [AdminController::class, 'doc'])->name('doc');
    Route::get('/stat', [AdminController::class, 'stat'])->name('stat');
});

// ผู้ดูแลระบบ (admin only) - Admin สามารถเข้าถึงทุกหน้าได้
Route::prefix('admin')->middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/performance', [AdminController::class, 'index'])->name('performance');
    Route::get('/delete/{id}', [AdminController::class, 'delete'])->name('delete');
    Route::get('/change/{id}', [AdminController::class, 'change'])->name('change');
    Route::get('/toggle-featured/{id}', [AdminController::class, 'toggleFeatured'])->name('toggle.featured');
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [AdminController::class, 'update'])->name('update');
    Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk.action');
    Route::get('/backup', [AdminController::class, 'backup'])->name('backup');
    Route::post('/restore', [AdminController::class, 'restore'])->name('restore');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
});

// ข้อมูลส่วนตัว (User และ Admin สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    // User และ Admin สามารถสร้างและบันทึกข้อมูลส่วนตัวได้
    Route::get('/personal-info/create', [PersonalInfoController::class, 'create'])->name('personal-info.create');
    Route::post('/personal-info', [PersonalInfoController::class, 'store'])->name('personal-info.store');
    
    // User สามารถแก้ไขผลงานของตัวเองได้
    Route::get('/personal-info/{personalInfo}/edit-my', [PersonalInfoController::class, 'editMy'])->name('personal-info.edit-my');
    Route::put('/personal-info/{personalInfo}/update-my', [PersonalInfoController::class, 'updateMy'])->name('personal-info.update-my');
});

// ข้อมูลส่วนตัว (Admin เท่านั้น)
Route::middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/personal-info', [PersonalInfoController::class, 'index'])->name('personal-info.index');
    
    // Routes สำหรับการอนุมัติ (ต้องอยู่ก่อน route ที่มี parameter)
    Route::get('/personal-info/pending', [PersonalInfoController::class, 'pending'])->name('personal-info.pending');
    Route::get('/personal-info/approved', [PersonalInfoController::class, 'approved'])->name('personal-info.approved');
    Route::get('/personal-info/rejected', [PersonalInfoController::class, 'rejected'])->name('personal-info.rejected');
    
    Route::get('/personal-info/{personalInfo}/edit', [PersonalInfoController::class, 'edit'])->name('personal-info.edit');
    Route::put('/personal-info/{personalInfo}', [PersonalInfoController::class, 'update'])->name('personal-info.update');
    Route::delete('/personal-info/{personalInfo}', [PersonalInfoController::class, 'destroy'])->name('personal-info.destroy');
    
    Route::post('/personal-info/{personalInfo}/approve', [PersonalInfoController::class, 'approve'])->name('personal-info.approve');
    Route::post('/personal-info/{personalInfo}/reject', [PersonalInfoController::class, 'reject'])->name('personal-info.reject');
    Route::post('/personal-info/{personalInfo}/cancel-approval', [PersonalInfoController::class, 'cancelApproval'])->name('personal-info.cancel-approval');
    
    // Export to PDF
    Route::get('/personal-info/export', [PersonalInfoController::class, 'showExportForm'])->name('personal-info.export-form');
    Route::get('/personal-info/export/pdf', [PersonalInfoController::class, 'exportToPdf'])->name('personal-info.export-pdf');
    Route::get('/personal-info/preview-export', [PersonalInfoController::class, 'previewExport'])->name('personal-info.preview-export');
});

// ข้อมูลส่วนตัว (ทุกคนสามารถดูรายละเอียดได้)
Route::get('/personal-info/{personalInfo}', [PersonalInfoController::class, 'show'])->name('personal-info.show');
Route::get('/personal-info/{personalInfo}/download', [PersonalInfoController::class, 'download'])->name('personal-info.download');

// API สำหรับ Dynamic Dropdown
Route::get('/api/majors-by-faculty', [PersonalInfoController::class, 'getMajorsByFaculty'])->name('api.majors-by-faculty');

// Routes สำหรับดูหน้าตา User (Admin เท่านั้น)
Route::middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/user-view', [UserViewController::class, 'index'])->name('user-view.index');
    Route::get('/user-view/{userId}', [UserViewController::class, 'show'])->name('user-view.show');
    Route::get('/user-view/{userId}/dashboard', [UserViewController::class, 'dashboard'])->name('user-view.dashboard');
    Route::get('/user-view/{userId}/edit', [UserViewController::class, 'edit'])->name('user-view.edit');
    Route::put('/user-view/{userId}', [UserViewController::class, 'update'])->name('user-view.update');
    Route::delete('/user-view/{userId}', [UserViewController::class, 'destroy'])->name('user-view.destroy');
});



// Authentication Routes (Custom)
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes (Custom)
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset Routes (Custom - Using Code)
Route::get('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\PasswordResetController::class, 'sendResetCode'])->name('password.email');
Route::get('/password/reset/{email}/{code}', [App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.update');
Route::post('/password/resend', [App\Http\Controllers\PasswordResetController::class, 'resendCode'])->name('password.resend');
Route::post('/password/verify', [App\Http\Controllers\PasswordResetController::class, 'verifyCode'])->name('password.verify');

// Dashboard route (User และ Admin สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
});

// Notification Routes (User และ Admin สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/unread-list', [App\Http\Controllers\NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread-list');
});

// Rating Routes (User และ Admin สามารถเข้าถึงได้)
Route::middleware(['auth'])->group(function () {
    Route::post('/portfolios/{personalInfo}/rate', [App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
    Route::get('/portfolios/{personalInfo}/my-rating', [App\Http\Controllers\RatingController::class, 'getUserRating'])->name('ratings.user');
    Route::delete('/portfolios/{personalInfo}/rating', [App\Http\Controllers\RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::get('/portfolios/{personalInfo}/ratings', [App\Http\Controllers\RatingController::class, 'getPortfolioRatings'])->name('ratings.portfolio');
    // Realtime stats endpoint
    Route::get('/portfolios/{personalInfo}/realtime-stats', [App\Http\Controllers\RatingController::class, 'realtimeStats'])->name('ratings.realtime');
});

// Redirect old portfolio routes to personal-info
Route::get('/portfolios/{id}', function($id) {
    return redirect()->route('personal-info.show', $id);
})->name('portfolios.show');



// Admin routes
Route::middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/admin', fn() => "Welcome Admin");
    
    // User Management Routes
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{userId}/password-reset', [AdminController::class, 'showPasswordResetForm'])->name('admin.users.password-reset');
    Route::post('/admin/users/{userId}/password-reset', [AdminController::class, 'resetUserPassword'])->name('admin.users.password-reset.store');
    Route::get('/admin/users/{userId}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{userId}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{userId}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

// Guest routes (สำหรับ visitor)
Route::get('/guest', fn() => "Welcome Visitor - You are not logged in")->name('guest');








