@echo off
echo ========================================
echo   เตรียมโปรเจค Portfolio Online
echo   สำหรับการนำเสนอ
echo ========================================
echo.

echo [1/5] สร้างโฟลเดอร์สำหรับการนำเสนอ...
if not exist "presentation" mkdir presentation
if not exist "presentation\project-portfolio" mkdir presentation\project-portfolio

echo [2/5] คัดลอกไฟล์โปรเจค...
xcopy /E /I /Y "project-portfolio" "presentation\project-portfolio"

echo [3/5] คัดลอกไฟล์ฐานข้อมูล...
copy "database\database.sqlite" "presentation\database.sqlite"

echo [4/5] สร้างไฟล์ .env สำหรับการนำเสนอ...
echo APP_NAME="Portfolio Online" > presentation\.env
echo APP_ENV=production >> presentation\.env
echo APP_KEY=base64:your-key-here >> presentation\.env
echo APP_DEBUG=false >> presentation\.env
echo APP_URL=http://localhost:8000 >> presentation\.env
echo. >> presentation\.env
echo DB_CONNECTION=sqlite >> presentation\.env
echo DB_DATABASE=database/database.sqlite >> presentation\.env
echo. >> presentation\.env
echo CACHE_DRIVER=file >> presentation\.env
echo SESSION_DRIVER=file >> presentation\.env
echo QUEUE_CONNECTION=sync >> presentation\.env

echo [5/5] สร้างไฟล์ README...
copy "README_PRESENTATION.md" "presentation\README.md"

echo.
echo ========================================
echo   เสร็จสิ้น! โปรเจคพร้อมสำหรับการนำเสนอ
echo ========================================
echo.
echo โฟลเดอร์ที่สร้าง: presentation\
echo.
echo วิธีใช้งาน:
echo 1. คัดลอกโฟลเดอร์ presentation\ ไปยังเครื่องใหม่
echo 2. เปิด Terminal/Command Prompt
echo 3. ไปที่โฟลเดอร์ project-portfolio
echo 4. รันคำสั่ง: php artisan serve
echo 5. เปิดเบราว์เซอร์ไปที่: http://localhost:8000
echo.
pause
