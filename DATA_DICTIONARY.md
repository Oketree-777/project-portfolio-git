# Data Dictionary - Portfolio Management System

## ข้อมูลทั่วไป
- **ชื่อระบบ**: Portfolio Management System
- **เวอร์ชัน**: 1.0
- **วันที่สร้าง**: 2025-01-28
- **ฐานข้อมูล**: SQLite
- **Framework**: Laravel 11

---

## ตารางฐานข้อมูล

### 1. users
**คำอธิบาย**: ตารางเก็บข้อมูลผู้ใช้งานระบบ

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| name | varchar | 255 | NO | - | ชื่อผู้ใช้งาน |
| email | varchar | 255 | NO | - | อีเมล (Unique) |
| email_verified_at | timestamp | - | YES | NULL | เวลาที่ยืนยันอีเมล |
| role | varchar | 255 | NO | 'user' | บทบาท (user/admin) |
| password | varchar | 255 | NO | - | รหัสผ่าน (hashed) |
| remember_token | varchar | 100 | YES | NULL | Token สำหรับจำการเข้าสู่ระบบ |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (email)

**Relationships:**
- hasMany: personal_infos, documents, notifications, analytics

---

### 2. personal_info
**คำอธิบาย**: ตารางเก็บข้อมูลส่วนตัวและผลงานของนักศึกษา

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| user_id | bigint | - | YES | NULL | Foreign Key to users |
| title | varchar | 255 | NO | - | คำนำหน้า (นาย/นางสาว) |
| title_en | varchar | 255 | YES | NULL | คำนำหน้าภาษาอังกฤษ |
| first_name | varchar | 255 | NO | - | ชื่อ |
| last_name | varchar | 255 | NO | - | นามสกุล |
| first_name_en | varchar | 255 | YES | NULL | ชื่อภาษาอังกฤษ |
| last_name_en | varchar | 255 | YES | NULL | นามสกุลภาษาอังกฤษ |
| age | int | - | NO | - | อายุ |
| gender | enum | - | NO | - | เพศ (ชาย/หญิง) |
| faculty | varchar | 255 | NO | - | คณะ |
| major | varchar | 255 | NO | - | สาขา |
| education_level | varchar | 255 | YES | NULL | ระดับการศึกษา |
| study_plan | varchar | 255 | YES | NULL | แผนการเรียน |
| institution | varchar | 255 | YES | NULL | สถาบันการศึกษา |
| province | varchar | 255 | YES | NULL | จังหวัด |
| gpa | decimal | 3,2 | YES | NULL | ผลการเรียนเฉลี่ย |
| subject_groups | json | - | YES | NULL | กลุ่มสาระการเรียนรู้ |
| subject_gpa | json | - | YES | NULL | ผลการเรียนรายวิชา |
| national_id | varchar | 13 | YES | NULL | เลขบัตรประจำตัวประชาชน |
| house_number | varchar | 255 | YES | NULL | บ้านเลขที่ |
| village_no | varchar | 255 | YES | NULL | หมู่ |
| road | varchar | 255 | YES | NULL | ถนน |
| sub_district | varchar | 255 | YES | NULL | ตำบล |
| district | varchar | 255 | YES | NULL | อำเภอ |
| province_address | varchar | 255 | YES | NULL | จังหวัด (ที่อยู่) |
| postal_code | varchar | 5 | YES | NULL | รหัสไปรษณีย์ |
| phone | varchar | 10 | YES | NULL | เบอร์โทรศัพท์ |
| major_code | varchar | 255 | YES | NULL | รหัสสาขาวิชา |
| major_name | varchar | 255 | YES | NULL | ชื่อสาขาวิชา |
| program | varchar | 255 | YES | NULL | หลักสูตร |
| receipt_book_no | varchar | 255 | YES | NULL | เล่มที่ |
| receipt_no | varchar | 255 | YES | NULL | เลขที่ |
| amount | decimal | 10,2 | YES | NULL | จำนวนเงิน |
| documents | json | - | YES | NULL | หลักฐานการสมัคร |
| verifier_name | varchar | 255 | YES | NULL | ชื่อผู้ตรวจหลักฐาน |
| recipient_name | varchar | 255 | YES | NULL | ชื่อผู้รับเงิน |
| photo | varchar | 255 | YES | NULL | รูปภาพ |
| portfolio_cover | varchar | 255 | YES | NULL | ภาพปก Portfolio |
| portfolio_file | varchar | 255 | YES | NULL | ไฟล์ Portfolio |
| portfolio_filename | varchar | 255 | YES | NULL | ชื่อไฟล์ Portfolio |
| status | enum | - | NO | 'pending' | สถานะ (pending/approved/rejected) |
| rejection_reason | text | - | YES | NULL | เหตุผลที่ไม่อนุมัติ |
| approved_by | bigint | - | YES | NULL | Foreign Key to users (ผู้อนุมัติ) |
| approved_at | timestamp | - | YES | NULL | เวลาที่อนุมัติ |
| rejected_by | bigint | - | YES | NULL | Foreign Key to users (ผู้ปฏิเสธ) |
| rejected_at | timestamp | - | YES | NULL | เวลาที่ปฏิเสธ |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
- FOREIGN KEY (rejected_by) REFERENCES users(id) ON DELETE SET NULL

**Relationships:**
- belongsTo: user, approvedBy, rejectedBy
- hasMany: analytics (as portfolio)

---

### 3. performances
**คำอธิบาย**: ตารางเก็บข้อมูลผลงาน/โปรเจคของนักศึกษา

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| title | varchar | 255 | NO | - | ชื่อผลงาน |
| content | text | - | NO | - | รายละเอียดผลงาน |
| status | boolean | - | NO | true | สถานะการแสดงผล |
| image | varchar | 255 | YES | NULL | รูปภาพผลงาน |
| category | varchar | 255 | YES | NULL | หมวดหมู่ผลงาน |
| description | text | - | YES | NULL | คำอธิบายเพิ่มเติม |
| github_url | varchar | 255 | YES | NULL | URL ของ GitHub |
| live_url | varchar | 255 | YES | NULL | URL ของเว็บไซต์ |
| tags | json | - | YES | NULL | แท็ก/ป้ายกำกับ |
| views | int | - | NO | 0 | จำนวนการดู |
| featured | boolean | - | NO | false | ผลงานเด่น |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)

**Relationships:**
- ไม่มี relationships กับตารางอื่น

---

### 4. documents
**คำอธิบาย**: ตารางเก็บข้อมูลเอกสารที่อัปโหลด

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| title | varchar | 255 | NO | - | ชื่อเอกสาร |
| content | text | - | NO | - | รายละเอียดเอกสาร |
| file_path | varchar | 255 | YES | NULL | เส้นทางไฟล์ |
| original_filename | varchar | 255 | YES | NULL | ชื่อไฟล์เดิม |
| user_id | bigint | - | NO | - | Foreign Key to users |
| status | enum | - | NO | 'pending' | สถานะ (pending/approved/rejected) |
| rejection_reason | text | - | YES | NULL | เหตุผลที่ไม่อนุมัติ |
| approved_by | bigint | - | YES | NULL | Foreign Key to users (ผู้อนุมัติ) |
| approved_at | timestamp | - | YES | NULL | เวลาที่อนุมัติ |
| rejected_by | bigint | - | YES | NULL | Foreign Key to users (ผู้ปฏิเสธ) |
| rejected_at | timestamp | - | YES | NULL | เวลาที่ปฏิเสธ |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
- FOREIGN KEY (rejected_by) REFERENCES users(id) ON DELETE SET NULL

**Relationships:**
- belongsTo: user, approver, rejecter

---

### 5. analytics
**คำอธิบาย**: ตารางเก็บข้อมูลการวิเคราะห์และสถิติการใช้งาน

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| event_type | varchar | 255 | NO | - | ประเภทเหตุการณ์ (page_view, portfolio_view, download, search) |
| event_name | varchar | 255 | NO | - | ชื่อเหตุการณ์ |
| event_data | json | - | YES | NULL | ข้อมูลเพิ่มเติม (JSON) |
| user_agent | varchar | 255 | YES | NULL | ข้อมูล Browser/Device |
| ip_address | varchar | 255 | YES | NULL | IP Address |
| session_id | varchar | 255 | YES | NULL | Session ID |
| user_id | bigint | - | YES | NULL | Foreign Key to users |
| portfolio_id | bigint | - | YES | NULL | Foreign Key to personal_info |
| page_url | varchar | 255 | YES | NULL | URL ที่เข้าชม |
| referrer | varchar | 255 | YES | NULL | มาจากหน้าไหน |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (event_type, created_at)
- INDEX (user_id, created_at)
- INDEX (portfolio_id, created_at)
- INDEX (created_at)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
- FOREIGN KEY (portfolio_id) REFERENCES personal_info(id) ON DELETE SET NULL

**Relationships:**
- belongsTo: user, portfolio

---

### 6. notifications
**คำอธิบาย**: ตารางเก็บข้อมูลการแจ้งเตือน

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| user_id | bigint | - | NO | - | Foreign Key to users |
| type | varchar | 255 | NO | - | ประเภทการแจ้งเตือน (approval, rejection, system) |
| title | varchar | 255 | NO | - | หัวข้อการแจ้งเตือน |
| message | text | - | NO | - | ข้อความการแจ้งเตือน |
| action_url | varchar | 255 | YES | NULL | URL สำหรับการดำเนินการ |
| is_read | boolean | - | NO | false | สถานะการอ่าน |
| read_at | timestamp | - | YES | NULL | เวลาที่อ่าน |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE

**Relationships:**
- belongsTo: user

---

### 7. password_reset_codes
**คำอธิบาย**: ตารางเก็บรหัสสำหรับรีเซ็ตรหัสผ่าน

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| email | varchar | 255 | NO | - | อีเมล |
| code | varchar | 6 | NO | - | รหัส 6 หลัก |
| expires_at | timestamp | - | NO | - | เวลาหมดอายุ |
| used | boolean | - | NO | false | สถานะการใช้งาน |
| used_at | timestamp | - | YES | NULL | เวลาที่ใช้ |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |
| updated_at | timestamp | - | YES | NULL | เวลาที่อัปเดต |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (email)

**Relationships:**
- ไม่มี relationships กับตารางอื่น

---

### 8. password_reset_tokens
**คำอธิบาย**: ตารางเก็บ token สำหรับรีเซ็ตรหัสผ่าน (Laravel Default)

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| email | varchar | 255 | NO | - | อีเมล (Primary Key) |
| token | varchar | 255 | NO | - | Token |
| created_at | timestamp | - | YES | NULL | เวลาที่สร้าง |

**Indexes:**
- PRIMARY KEY (email)

**Relationships:**
- ไม่มี relationships กับตารางอื่น

---

### 9. sessions
**คำอธิบาย**: ตารางเก็บข้อมูล session (Laravel Default)

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | varchar | 255 | NO | - | Session ID (Primary Key) |
| user_id | bigint | - | YES | NULL | Foreign Key to users |
| ip_address | varchar | 45 | YES | NULL | IP Address |
| user_agent | text | - | YES | NULL | User Agent |
| payload | longtext | - | NO | - | Session Data |
| last_activity | int | - | NO | - | เวลากิจกรรมล่าสุด |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (user_id)
- INDEX (last_activity)

**Relationships:**
- belongsTo: user

---

### 10. cache
**คำอธิบาย**: ตารางเก็บข้อมูล cache (Laravel Default)

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| key | varchar | 255 | NO | - | Cache Key (Primary Key) |
| value | mediumtext | - | NO | - | Cache Value |
| expiration | int | - | NO | - | เวลาหมดอายุ |

**Indexes:**
- PRIMARY KEY (key)

**Relationships:**
- ไม่มี relationships กับตารางอื่น

---

### 11. jobs
**คำอธิบาย**: ตารางเก็บข้อมูล job queue (Laravel Default)

| ชื่อฟิลด์ | ประเภทข้อมูล | ความยาว | NULL | ค่าเริ่มต้น | คำอธิบาย |
|-----------|-------------|---------|------|------------|----------|
| id | bigint | - | NO | auto_increment | Primary Key |
| queue | varchar | 255 | NO | - | ชื่อ queue |
| payload | longtext | - | NO | - | ข้อมูล job |
| attempts | tinyint | - | NO | - | จำนวนครั้งที่พยายาม |
| reserved_at | int | - | YES | NULL | เวลาที่จอง |
| available_at | int | - | NO | - | เวลาที่พร้อมใช้งาน |
| created_at | int | - | NO | - | เวลาที่สร้าง |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (queue)

**Relationships:**
- ไม่มี relationships กับตารางอื่น

---

## Entity Relationship Diagram (ERD)

```
users (1) -----> (0..n) personal_info
users (1) -----> (0..n) documents
users (1) -----> (0..n) notifications
users (1) -----> (0..n) analytics
users (1) -----> (0..n) sessions

personal_info (1) -----> (0..n) analytics
personal_info (0..1) -----> (1) users [approved_by]
personal_info (0..1) -----> (1) users [rejected_by]

documents (0..1) -----> (1) users [approved_by]
documents (0..1) -----> (1) users [rejected_by]
```

---

## Business Rules

### 1. User Management
- ผู้ใช้ต้องมีอีเมลที่ไม่ซ้ำกัน
- บทบาทของผู้ใช้: user, admin
- Admin สามารถเข้าถึงทุกฟีเจอร์ได้

### 2. Personal Info Management
- นักศึกษาสามารถสร้างข้อมูลส่วนตัวได้หลายรายการ
- สถานะ: pending (รอการอนุมัติ), approved (อนุมัติแล้ว), rejected (ไม่อนุมัติ)
- ต้องมีผู้อนุมัติหรือผู้ปฏิเสธเป็น admin

### 3. Document Management
- เอกสารต้องเชื่อมโยงกับผู้ใช้
- สถานะการอนุมัติเหมือนกับ personal_info

### 4. Analytics
- บันทึกทุกการกระทำของผู้ใช้
- รองรับการวิเคราะห์สถิติการใช้งาน

### 5. Notifications
- แจ้งเตือนเมื่อมีการอนุมัติ/ปฏิเสธ
- แจ้งเตือนระบบทั่วไป

---

## Data Types Reference

| ประเภทข้อมูล | คำอธิบาย |
|-------------|----------|
| bigint | จำนวนเต็มขนาดใหญ่ (64-bit) |
| varchar(n) | ข้อความความยาวไม่เกิน n ตัวอักษร |
| text | ข้อความความยาวไม่จำกัด |
| longtext | ข้อความความยาวมาก |
| mediumtext | ข้อความความยาวปานกลาง |
| json | ข้อมูล JSON |
| decimal(p,s) | จำนวนทศนิยม p หลัก s ตำแหน่ง |
| boolean | ค่าจริง/เท็จ |
| enum | ค่าจากรายการที่กำหนด |
| timestamp | เวลาและวันที่ |
| tinyint | จำนวนเต็มขนาดเล็ก (8-bit) |
| int | จำนวนเต็ม (32-bit) |

---

## Indexes Summary

### Primary Keys
- users.id
- personal_info.id
- performances.id
- documents.id
- analytics.id
- notifications.id
- password_reset_codes.id
- password_reset_tokens.email
- sessions.id
- cache.key
- jobs.id

### Foreign Keys
- personal_info.user_id → users.id
- personal_info.approved_by → users.id
- personal_info.rejected_by → users.id
- documents.user_id → users.id
- documents.approved_by → users.id
- documents.rejected_by → users.id
- analytics.user_id → users.id
- analytics.portfolio_id → personal_info.id
- notifications.user_id → users.id
- sessions.user_id → users.id

### Performance Indexes
- analytics: (event_type, created_at), (user_id, created_at), (portfolio_id, created_at), (created_at)
- password_reset_codes: (email)
- sessions: (user_id), (last_activity)
- jobs: (queue)

---

## Notes

1. **Security**: รหัสผ่านถูก hash ด้วย Laravel's built-in hashing
2. **Soft Deletes**: ไม่ได้ใช้ soft deletes ในระบบนี้
3. **Timestamps**: ทุกตารางมี created_at และ updated_at (ยกเว้น cache, jobs)
4. **JSON Fields**: ใช้ JSON สำหรับข้อมูลที่ซับซ้อน เช่น subject_groups, event_data
5. **File Storage**: ไฟล์ถูกเก็บใน storage/app/public และมี path ในฐานข้อมูล
6. **Analytics**: ระบบวิเคราะห์ข้อมูลครอบคลุมการใช้งานทุกส่วน
7. **Approval Workflow**: มีระบบอนุมัติสำหรับ personal_info และ documents

---

*เอกสารนี้สร้างขึ้นโดยอัตโนมัติจากโครงสร้างฐานข้อมูล Laravel Migration*

