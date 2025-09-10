# 🎯 คู่มือการติดตั้งโปรเจค Portfolio Online สำหรับการนำเสนอ

## 📋 สิ่งที่ต้องเตรียม

### **1. ระบบที่ต้องมี**
- PHP 8.2 หรือสูงกว่า
- Composer
- SQLite หรือ MySQL
- Web Server (Apache/Nginx) หรือใช้ PHP Built-in Server

### **2. ไฟล์ที่ต้องมี**
- โปรเจคทั้งหมด (โฟลเดอร์ project-portfolio)
- ไฟล์ฐานข้อมูล (database.sqlite)
- ไฟล์ .env สำหรับการตั้งค่า

## 🚀 ขั้นตอนการติดตั้ง

### **ขั้นตอนที่ 1: เตรียมไฟล์**
```bash
# 1. คัดลอกโปรเจคไปยังเครื่องใหม่
# 2. เปิด Terminal/Command Prompt
# 3. ไปที่โฟลเดอร์โปรเจค
cd project-portfolio
```

### **ขั้นตอนที่ 2: ติดตั้ง Dependencies**
```bash
# ติดตั้ง PHP packages
composer install

# หรือถ้าไม่มี composer
# ดาวน์โหลด vendor folder จากเครื่องเดิม
```

### **ขั้นตอนที่ 3: ตั้งค่าฐานข้อมูล**
```bash
# คัดลอกไฟล์ .env.example เป็น .env
cp .env.example .env

# แก้ไขไฟล์ .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### **ขั้นตอนที่ 4: รันโปรเจค**
```bash
# วิธีที่ 1: ใช้ PHP Built-in Server (แนะนำ)
php artisan serve --host=0.0.0.0 --port=8000

# วิธีที่ 2: ใช้ XAMPP/WAMP
# คัดลอกโปรเจคไปยัง htdocs/www แล้วเปิดผ่าน localhost
```

### **ขั้นตอนที่ 5: เข้าสู่ระบบ**
```
URL: http://localhost:8000
หรือ http://127.0.0.1:8000

บัญชี Admin:
Email: admin@example.com
Password: password

บัญชี User:
Email: user@example.com  
Password: password
```

## 🔧 การแก้ไขปัญหา

### **ปัญหา: ไม่พบไฟล์ vendor/**
```bash
# ติดตั้ง Composer แล้วรัน
composer install
```

### **ปัญหา: ไม่สามารถเชื่อมต่อฐานข้อมูล**
```bash
# ตรวจสอบไฟล์ .env
# ตรวจสอบไฟล์ database.sqlite มีอยู่
# ตรวจสอบสิทธิ์การเขียนไฟล์
```

### **ปัญหา: ไม่สามารถอัปโหลดไฟล์**
```bash
# สร้างโฟลเดอร์ storage
mkdir storage/app/public
mkdir storage/app/public/uploads

# สร้าง symbolic link
php artisan storage:link
```

## 📱 การนำเสนอ

### **1. เตรียมข้อมูล**
- สร้างผลงานตัวอย่าง 2-3 ชิ้น
- สร้างผู้ใช้ตัวอย่าง (Admin, User)
- เตรียมเอกสารตัวอย่าง

### **2. การสาธิต**
1. **หน้าแรก** - แสดงผลงานทั้งหมด
2. **ระบบสมาชิก** - Login/Register
3. **หน้าผู้ใช้** - เพิ่มผลงาน, แก้ไขข้อมูล
4. **หน้าผู้ดูแล** - อนุมัติผลงาน, จัดการระบบ
5. **ระบบแจ้งเตือน** - แสดงการแจ้งเตือน

### **3. ฟีเจอร์เด่น**
- ✅ ระบบอนุมัติผลงาน
- ✅ การแจ้งเตือนแบบ Real-time
- ✅ สถิติและรายงาน
- ✅ ระบบค้นหาและกรอง
- ✅ Responsive Design

## 📞 ติดต่อ

หากมีปัญหาในการติดตั้ง กรุณาติดต่อ:
- Email: [your-email]
- Phone: [your-phone]

---
**หมายเหตุ:** โปรเจคนี้ใช้ Laravel 12.x และ PHP 8.2+ 
สำหรับการนำเสนอในสถานที่ที่ไม่มีอินเทอร์เน็ต ให้เตรียมไฟล์ vendor/ มาด้วย
