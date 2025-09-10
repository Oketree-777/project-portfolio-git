<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว - PORTFOLIO ONLINE</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', 'Roboto', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #1a1a1a;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 30px;
        }
        
        .header h2 {
            color: #000000;
            margin: 0 0 15px 0;
            font-size: 32px;
            font-weight: 300;
            letter-spacing: -0.5px;
        }
        
        .header hr {
            border: none;
            height: 1px;
            background-color: #e0e0e0;
            margin: 20px auto;
            width: 80px;
        }
        
        .header p {
            margin: 10px 0;
            color: #666666;
            font-size: 18px;
            font-weight: 400;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        th, td {
            border: 1px solid #f0f0f0;
            padding: 20px 24px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #fafafa;
            font-weight: 600;
            color: #1a1a1a;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 16px;
            color: #888888;
            border-top: 1px solid #e0e0e0;
            padding-top: 30px;
        }
        
        .footer p {
            margin: 10px 0;
            font-weight: 400;
        }
        
        .no-data {
            text-align: center;
            padding: 80px 40px;
            color: #888888;
            font-style: italic;
            font-size: 18px;
            background-color: #fafafa;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
        }
        
        .no-data p {
            margin: 0;
            font-weight: 400;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>รายชื่อผู้ผ่านการคัดเลือก</h2>
        <hr>
        <p>รายงานข้อมูลส่วนตัว - {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if($personalInfos->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%; text-align: center;">ลำดับที่</th>
                    <th style="width: 45%; text-align: center;">ชื่อ - นามสกุล</th>
                    <th style="width: 20%; text-align: center;">คณะ</th>
                    <th style="width: 20%; text-align: center;">สาขา</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personalInfos as $index => $personalInfo)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #1a1a1a;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 600; color: #1a1a1a;">
                                {{ $personalInfo->title }} {{ $personalInfo->first_name }} {{ $personalInfo->last_name }}
                            </div>
                        </td>
                        <td>
                            <div style="color: #666666; font-size: 15px;">
                                {{ $personalInfo->faculty }}
                            </div>
                        </td>
                        <td>
                            <div style="color: #666666; font-size: 15px;">
                                {{ $personalInfo->major }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>ไม่พบข้อมูลที่ตรงกับเงื่อนไขการกรอง</p>
        </div>
    @endif

    <div class="footer">
        <p>รายงานนี้ถูกสร้างโดยระบบรับนักศึกษา มหาวิทยาลัยราชภัฏบุรีรัมย์</p>
        <p>วันที่สร้างรายงาน: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>จำนวนรายการทั้งหมด: {{ $personalInfos->count() }} รายการ</p>
    </div>
</body>
</html>
