# ระบบ Authentication และ Authorization

## โครงสร้าง Role

ระบบนี้มี 3 ระดับสิทธิ์:

### 1. Visitor (ผู้เยี่ยมชม)
- ไม่ต้อง login
- สามารถดูผลงานได้
- ไม่สามารถเข้าถึง admin panel ได้

### 2. User (สมาชิกทั่วไป)
- ต้อง login
- สามารถดูผลงานได้
- ไม่สามารถเข้าถึง admin panel ได้

### 3. Admin (ผู้ดูแลระบบ)
- ต้อง login
- สามารถดูผลงานได้
- สามารถเข้าถึง admin panel ได้
- สามารถจัดการผลงาน (เพิ่ม, แก้ไข, ลบ) ได้

## การใช้งาน

### สร้าง Admin User

#### วิธีที่ 1: ใช้ Seeder
```bash
php artisan db:seed --class=AdminSeeder
```

#### วิธีที่ 2: ใช้ Artisan Command
```bash
php artisan make:admin "Admin Name" "admin@example.com" "password"
```

### ข้อมูล Login เริ่มต้น

**Admin:**
- Email: admin@example.com
- Password: password

**User:**
- Email: user@example.com
- Password: password

## Middleware

### CheckRole Middleware
ใช้สำหรับตรวจสอบสิทธิ์การเข้าถึง:

```php
// ตรวจสอบว่าเป็น admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // admin routes
});

// ตรวจสอบว่าเป็น user
Route::middleware(['auth', 'role:user'])->group(function () {
    // user routes
});

// ตรวจสอบว่าเป็น visitor (ไม่ได้ login)
Route::middleware(['role:visitor'])->group(function () {
    // visitor routes
});
```

## Helper Methods ใน User Model

```php
// ตรวจสอบว่าเป็น admin
$user->isAdmin();

// ตรวจสอบว่าเป็น user
$user->isUser();

// ตรวจสอบ role เฉพาะ
$user->hasRole('admin');
$user->hasRole('user');
```

## การจัดการ Error

- **403 Unauthorized**: แสดงเมื่อไม่มีสิทธิ์เข้าถึง
- **Redirect to Login**: เมื่อพยายามเข้าถึง protected routes โดยไม่ได้ login

## การทดสอบ

1. เข้าไปที่หน้าแรก (/) - ควรเข้าถึงได้โดยไม่ต้อง login
2. ลองเข้าถึง /admin/performance โดยไม่ได้ login - ควร redirect ไปหน้า login
3. Login ด้วย user account แล้วลองเข้าถึง admin routes - ควรแสดง 403 error
4. Login ด้วย admin account แล้วเข้าถึง admin routes - ควรเข้าถึงได้ปกติ
