# ฟีเจอร์การจัดการ Portfolio

## ฟีเจอร์ที่เพิ่มเข้ามา

### 1. การจัดการข้อมูลขั้นสูง

#### ฟิลด์ใหม่ใน Performance Model:
- **image** - รูปภาพผลงาน
- **category** - หมวดหมู่ (web, mobile, desktop, other)
- **description** - คำอธิบายเพิ่มเติม
- **github_url** - ลิงก์ GitHub
- **live_url** - ลิงก์ Live Demo
- **tags** - แท็ก (JSON array)
- **views** - จำนวนการเข้าชม
- **featured** - ผลงานแนะนำ

#### Helper Methods:
- `getImageUrlAttribute()` - ดึง URL รูปภาพ
- `incrementViews()` - เพิ่มจำนวนการเข้าชม
- `hasGithubUrl()`, `hasLiveUrl()` - ตรวจสอบ URL
- `getFormattedTagsAttribute()` - ดึงแท็กที่จัดรูปแบบแล้ว

#### Scopes:
- `featured()` - ผลงานแนะนำ
- `active()` - ผลงานที่เปิดใช้งาน
- `byCategory($category)` - ผลงานตามหมวดหมู่

### 2. การจัดการแบบ Bulk Actions

#### ฟีเจอร์ที่รองรับ:
- **เปิดใช้งานหลายรายการ** - เปิดใช้งานผลงานหลายชิ้นพร้อมกัน
- **ปิดใช้งานหลายรายการ** - ปิดใช้งานผลงานหลายชิ้นพร้อมกัน
- **ตั้งเป็นผลงานแนะนำ** - ตั้งผลงานหลายชิ้นเป็นแนะนำพร้อมกัน
- **ยกเลิกผลงานแนะนำ** - ยกเลิกผลงานแนะนำหลายชิ้นพร้อมกัน
- **ลบหลายรายการ** - ลบผลงานหลายชิ้นพร้อมกัน

#### การใช้งาน:
```php
// ใน Controller
Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk.action');
```

### 3. การสำรองข้อมูลและกู้คืน

#### Backup (JSON):
- ส่งออกข้อมูลทั้งหมดเป็นไฟล์ JSON
- รวมข้อมูล: exported_at, total_records, performances
- ไฟล์ชื่อ: `portfolio_backup_YYYY-MM-DD_HH-mm-ss.json`

#### Restore:
- นำเข้าข้อมูลจากไฟล์ JSON
- ลบข้อมูลเดิมและแทนที่ด้วยข้อมูลใหม่
- ตรวจสอบความถูกต้องของไฟล์

#### Export (CSV):
- ส่งออกข้อมูลเป็นไฟล์ CSV
- ข้อมูล: ID, Title, Content, Category, Status, Featured, Views, Created At
- ไฟล์ชื่อ: `portfolio_export_YYYY-MM-DD_HH-mm-ss.csv`

### 4. Service Layer

#### PerformanceService:
- **create()** - สร้างผลงานใหม่
- **update()** - อัพเดทผลงาน
- **delete()** - ลบผลงาน
- **uploadImage()** - อัพโหลดรูปภาพ
- **deleteImage()** - ลบรูปภาพ
- **parseTags()** - แปลงแท็กจาก string เป็น array
- **getStatistics()** - ดึงสถิติ
- **getTopPerformances()** - ดึงผลงานยอดนิยม
- **search()** - ค้นหาผลงาน

### 5. Artisan Commands

#### performance:manage Command:
```bash
# แสดงรายการผลงาน
php artisan performance:manage list

# สร้างผลงานใหม่
php artisan performance:manage create --title="Project Title" --content="Description" --category="web"

# อัพเดทผลงาน
php artisan performance:manage update --id=1 --title="New Title"

# ลบผลงาน
php artisan performance:manage delete --id=1

# แสดงสถิติ
php artisan performance:manage stats

# ล้างข้อมูลที่ไม่ใช้งาน
php artisan performance:manage cleanup
```

#### make:admin Command:
```bash
# สร้าง admin user
php artisan make:admin "Admin Name" "admin@example.com" "password"
```

### 6. การจัดการรูปภาพ

#### อัพโหลดรูปภาพ:
- รองรับไฟล์: jpeg, png, jpg, gif
- ขนาดสูงสุด: 2MB
- เก็บใน: `storage/app/public/performances/`
- ลิงก์ผ่าน: `php artisan storage:link`

#### การจัดการ:
- อัพโหลดอัตโนมัติเมื่อสร้าง/อัพเดท
- ลบรูปภาพเก่าอัตโนมัติเมื่ออัพโหลดใหม่
- ลบรูปภาพเมื่อลบผลงาน

### 7. ระบบค้นหาและกรอง

#### ค้นหา:
- ค้นหาใน title, content, description
- รองรับ pagination
- เรียงลำดับตาม featured และ created_at

#### กรอง:
- ตามหมวดหมู่ (category)
- ตามสถานะ (status)
- ตามผลงานแนะนำ (featured)

### 8. สถิติและการวิเคราะห์

#### สถิติพื้นฐาน:
- จำนวนผลงานทั้งหมด
- จำนวนผลงานที่เปิดใช้งาน
- จำนวนผลงานแนะนำ
- จำนวนการเข้าชมทั้งหมด
- จำนวนการเข้าชมเฉลี่ย

#### สถิติตามหมวดหมู่:
- จำนวนผลงานในแต่ละหมวดหมู่
- เปอร์เซ็นต์การกระจาย

#### ผลงานยอดนิยม:
- เรียงตามจำนวนการเข้าชม
- แสดงผลงานที่ได้รับความนิยมสูงสุด

## การใช้งาน

### 1. การเข้าถึง Admin Panel:
```
URL: /admin/performance
Login: admin@example.com / password
```

### 2. การจัดการผลงาน:
- **เพิ่มผลงาน**: `/admin/create`
- **แก้ไขผลงาน**: `/admin/edit/{id}`
- **ลบผลงาน**: `/admin/delete/{id}`
- **เปลี่ยนสถานะ**: `/admin/change/{id}`
- **ตั้งเป็นแนะนำ**: `/admin/toggle-featured/{id}`

### 3. การสำรองข้อมูล:
- **Backup**: `/admin/backup`
- **Restore**: `/admin/restore` (POST)
- **Export**: `/admin/export`

### 4. การใช้งาน Command Line:
```bash
# ดูรายการผลงาน
php artisan performance:manage list

# ดูสถิติ
php artisan performance:manage stats

# สร้างผลงานใหม่
php artisan performance:manage create
```

## ข้อควรระวัง

1. **การสำรองข้อมูล**: ควรทำ backup อย่างสม่ำเสมอ
2. **การลบข้อมูล**: การลบข้อมูลไม่สามารถกู้คืนได้
3. **การอัพโหลดรูปภาพ**: ตรวจสอบขนาดและประเภทไฟล์
4. **สิทธิ์การเข้าถึง**: เฉพาะ admin เท่านั้นที่สามารถเข้าถึงฟีเจอร์เหล่านี้ได้
