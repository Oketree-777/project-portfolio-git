<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีเซ็ตรหัสผ่าน</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .reset-code {
            background-color: #f8fafc;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            font-size: 32px;
            font-weight: bold;
            color: #3b82f6;
            letter-spacing: 5px;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #92400e;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">PORTFOLIO ONLINE</div>
            <h2>รหัสการรีเซ็ตรหัสผ่าน</h2>
        </div>

        <p>สวัสดีคุณ <strong>{{ $userName }}</strong></p>

        <p>เราได้รับคำขอรีเซ็ตรหัสผ่านสำหรับบัญชีของคุณ หากคุณไม่ได้ทำการขอรหัสนี้ กรุณาละเว้นอีเมลนี้</p>

        <div class="reset-code">
            {{ $resetCode }}
        </div>

        <p><strong>คำแนะนำ:</strong></p>
        <ul>
            <li>รหัสนี้จะหมดอายุในวันที่: <strong>{{ $expiresAt }}</strong></li>
            <li>กรุณาใช้รหัสนี้ภายใน 60 นาที</li>
            <li>ห้ามแชร์รหัสนี้กับผู้อื่น</li>
        </ul>

        <div class="warning">
            <strong>⚠️ คำเตือน:</strong> หากคุณไม่ได้ขอรหัสนี้ กรุณาตรวจสอบความปลอดภัยของบัญชีของคุณและเปลี่ยนรหัสผ่านทันที
        </div>

        <p>หากคุณมีปัญหาในการรีเซ็ตรหัสผ่าน กรุณาติดต่อผู้ดูแลระบบ</p>

        <div class="footer">
            <p>อีเมลนี้ถูกส่งจากระบบ Portfolio Management System</p>
            <p>© {{ date('Y') }} Portfolio Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
