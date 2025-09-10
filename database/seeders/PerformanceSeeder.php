<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Performance;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $performances = [
            [
                'title' => 'Portfolio Website',
                'content' => 'เว็บไซต์ Portfolio ที่สร้างด้วย Laravel และ Bootstrap',
                'description' => 'เว็บไซต์แสดงผลงานส่วนตัวที่พัฒนาด้วย Laravel Framework และ Bootstrap 5 มีระบบจัดการผลงาน การอัพโหลดรูปภาพ และระบบ Authentication',
                'category' => 'web',
                'github_url' => 'https://github.com/example/portfolio',
                'live_url' => 'https://portfolio.example.com',
                'tags' => ['Laravel', 'Bootstrap', 'PHP', 'MySQL'],
                'featured' => true,
                'status' => true,
                'views' => 150,
            ],
            [
                'title' => 'E-Commerce Mobile App',
                'content' => 'แอปพลิเคชันขายของออนไลน์สำหรับมือถือ',
                'description' => 'แอปพลิเคชัน E-Commerce ที่พัฒนาด้วย React Native มีระบบชำระเงิน การจัดการสินค้า และระบบสมาชิก',
                'category' => 'mobile',
                'github_url' => 'https://github.com/example/ecommerce-app',
                'live_url' => 'https://play.google.com/store/apps/details?id=com.example.ecommerce',
                'tags' => ['React Native', 'JavaScript', 'Firebase', 'Redux'],
                'featured' => true,
                'status' => true,
                'views' => 89,
            ],
            [
                'title' => 'Desktop Task Manager',
                'content' => 'โปรแกรมจัดการงานสำหรับ Desktop',
                'description' => 'โปรแกรมจัดการงานที่พัฒนาด้วย Electron และ Vue.js มีระบบแจ้งเตือน การจัดลำดับความสำคัญ และการซิงค์ข้อมูล',
                'category' => 'desktop',
                'github_url' => 'https://github.com/example/task-manager',
                'live_url' => null,
                'tags' => ['Electron', 'Vue.js', 'Node.js', 'SQLite'],
                'featured' => false,
                'status' => true,
                'views' => 45,
            ],
            [
                'title' => 'Weather Dashboard',
                'content' => 'แดชบอร์ดแสดงข้อมูลสภาพอากาศแบบ Real-time',
                'description' => 'เว็บแอปพลิเคชันแสดงข้อมูลสภาพอากาศแบบ Real-time ที่พัฒนาด้วย Vue.js และ OpenWeatherMap API',
                'category' => 'web',
                'github_url' => 'https://github.com/example/weather-dashboard',
                'live_url' => 'https://weather.example.com',
                'tags' => ['Vue.js', 'API', 'Chart.js', 'CSS3'],
                'featured' => false,
                'status' => true,
                'views' => 67,
            ],
            [
                'title' => 'Fitness Tracking App',
                'content' => 'แอปพลิเคชันติดตามการออกกำลังกาย',
                'description' => 'แอปพลิเคชันติดตามการออกกำลังกายที่พัฒนาด้วย Flutter มีระบบติดตามกิจกรรม การคำนวณแคลอรี่ และการตั้งเป้าหมาย',
                'category' => 'mobile',
                'github_url' => 'https://github.com/example/fitness-app',
                'live_url' => 'https://apps.apple.com/app/fitness-tracker/id123456789',
                'tags' => ['Flutter', 'Dart', 'Firebase', 'Health Kit'],
                'featured' => false,
                'status' => true,
                'views' => 34,
            ],
            [
                'title' => 'Inventory Management System',
                'content' => 'ระบบจัดการคลังสินค้าสำหรับธุรกิจ',
                'description' => 'ระบบจัดการคลังสินค้าที่พัฒนาด้วย Laravel และ Vue.js มีระบบจัดการสินค้า การแจ้งเตือนสต็อก และรายงานการขาย',
                'category' => 'web',
                'github_url' => 'https://github.com/example/inventory-system',
                'live_url' => 'https://inventory.example.com',
                'tags' => ['Laravel', 'Vue.js', 'MySQL', 'Bootstrap'],
                'featured' => false,
                'status' => true,
                'views' => 23,
            ],
        ];

        foreach ($performances as $performance) {
            Performance::create($performance);
        }
    }
}
