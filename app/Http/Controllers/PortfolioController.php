<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        // ดึงผลงานที่ได้รับการอนุมัติทั้งหมด (แสดง 9 ผลงานต่อหน้า)
        $approvedPortfolios = PersonalInfo::where('status', 'approved')
            ->with('ratings')
            ->orderByDesc('approved_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        // ดึงหมวดหมู่คณะ
        $faculties = PersonalInfo::where('status', 'approved')
            ->select('faculty')
            ->distinct()
            ->pluck('faculty')
            ->filter()
            ->values();

        // ดึงหมวดหมู่สาขา
        $majors = PersonalInfo::where('status', 'approved')
            ->select('major')
            ->distinct()
            ->pluck('major')
            ->filter()
            ->values();

        // สถิติ
        $totalPortfolios = PersonalInfo::where('status', 'approved')->count();
        $totalFaculties = $faculties->count();
        $totalMajors = $majors->count();

        return view('portfolios.index', compact(
            'approvedPortfolios', 
            'faculties', 
            'majors', 
            'totalPortfolios', 
            'totalFaculties', 
            'totalMajors'
        ));
    }

    public function byFaculty($faculty)
    {
        // ดึงผลงานตามคณะ (แสดง 9 ผลงานต่อหน้า)
        $portfolios = PersonalInfo::where('status', 'approved')
            ->where('faculty', $faculty)
            ->with('ratings')
            ->orderByDesc('approved_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        // สถิติของคณะ
        $totalInFaculty = PersonalInfo::where('status', 'approved')
            ->where('faculty', $faculty)
            ->count();

        // ดึงสาขาในคณะนี้
        $majorsInFaculty = PersonalInfo::where('status', 'approved')
            ->where('faculty', $faculty)
            ->select('major')
            ->distinct()
            ->pluck('major')
            ->filter()
            ->values();

        return view('portfolios.by-faculty', compact(
            'portfolios', 
            'faculty', 
            'totalInFaculty', 
            'majorsInFaculty'
        ));
    }

    public function byMajor($major)
    {
        // ดึงผลงานตามสาขา (แสดง 9 ผลงานต่อหน้า)
        $portfolios = PersonalInfo::where('status', 'approved')
            ->where('major', $major)
            ->with('ratings')
            ->orderByDesc('approved_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        // สถิติของสาขา
        $totalInMajor = PersonalInfo::where('status', 'approved')
            ->where('major', $major)
            ->count();

        // ดึงคณะที่มีสาขานี้
        $facultiesWithMajor = PersonalInfo::where('status', 'approved')
            ->where('major', $major)
            ->select('faculty')
            ->distinct()
            ->pluck('faculty')
            ->filter()
            ->values();

        return view('portfolios.by-major', compact(
            'portfolios', 
            'major', 
            'totalInMajor', 
            'facultiesWithMajor'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $faculty = $request->get('faculty');
        $major = $request->get('major');
        $rating = $request->get('rating');

        $portfolios = PersonalInfo::where('status', 'approved')
            ->with('ratings');

        // ค้นหาตามคำค้น
        if ($query) {
            $portfolios->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('faculty', 'like', "%{$query}%")
                  ->orWhere('major', 'like', "%{$query}%");
            });
        }

        // กรองตามคณะ
        if ($faculty) {
            $portfolios->where('faculty', $faculty);
        }

        // กรองตามสาขา
        if ($major) {
            $portfolios->where('major', $major);
        }

        // กรองตามคะแนน
        if ($rating) {
            $portfolios->whereIn('id', function($query) use ($rating) {
                $query->select('personal_info_id')
                      ->from('ratings')
                      ->groupBy('personal_info_id')
                      ->havingRaw('AVG(rating) >= ?', [$rating]);
            });
        }

        $portfolios = $portfolios->orderByDesc('approved_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        // ดึงหมวดหมู่คณะทั้งหมด (ไม่ใช่แค่ที่มีผลงานอนุมัติ)
        $faculties = [
            'คณะครุศาสตร์',
            'คณะวิทยาศาสตร์',
            'คณะมนุษยศาสตร์และสังคมศาสตร์',
            'คณะวิทยาการจัดการ',
            'คณะเทคโนโลยีอุตสาหกรรม',
            'คณะเทคโนโลยีการเกษตร',
            'คณะพยาบาลศาสตร์',
            'บัณฑิตวิทยาลัย'
        ];

        // ข้อมูลคณะและสาขา (สำหรับ dynamic dropdown)
        $facultyMajors = [
            'คณะครุศาสตร์' => [
                'นาฏศิลป์', 'คณิตศาสตร์', 'การศึกษาปฐมวัย', 'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา',
                'สังคมศึกษา', 'ภาษาอังกฤษ', 'วิทยาศาสตร์ทั่วไป', 'ภาษาไทย', 'ศิลปศึกษา',
                'ดนตรีศึกษา', 'พลศึกษา', 'ฟิสิกส์'
            ],
            'คณะวิทยาศาสตร์' => [
                'ภูมิสารสนเทศ', 'เคมี', 'วิทยาศาสตร์สิ่งแวดล้อม', 'สาธารณสุขศาสตร์',
                'สถิติประยุกต์และวิทยาการสารสนเทศ', 'ชีววิทยา', 'เทคโนโลยีสารสนเทศ',
                'วิทยาการคอมพิวเตอร์', 'คณิตศาสตร์', 'วิทยาศาสตร์การกีฬา',
                'การออกแบบแฟชั่นและธุรกิจสิ่งทอ'
            ],
            'คณะมนุษยศาสตร์และสังคมศาสตร์' => [
                'การพัฒนาสังคม', 'ภาษาไทย', 'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์',
                'ภาษาอังกฤษ', 'ภาษาอังกฤษธุรกิจ', 'ดนตรีสากล', 'ศิลปะดิจิทัล',
                'รัฐประศาสนศาสตร์', 'นิติศาสตร์'
            ],
            'คณะวิทยาการจัดการ' => [
                'การบัญชี', 'การสื่อสารมวลชน', 'การท่องเที่ยวและการโรงแรม', 'เศรษฐศาสตร์',
                'การเงินและการธนาคาร', 'การจัดการ', 'การตลาด', 'การบริหารทรัพยากรมนุษย์',
                'คอมพิวเตอร์ธุรกิจ'
            ],
            'คณะเทคโนโลยีอุตสาหกรรม' => [
                'ศิลปะและการออกแบบ', 'เทคโนโลยีสถาปัตยกรรม', 'วิศวกรรมการจัดการอุตสาหกรรม',
                'เทคโนโลยีวิศวกรรมโยธา', 'เทคโนโลยีวิศวกรรมไฟฟ้า', 'เทคโนโลยีเซรามิกส์และการออกแบบ',
                'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์'
            ],
            'คณะเทคโนโลยีการเกษตร' => [
                'เกษตรศาสตร์ กลุ่มวิชานวัตกรรมการผลิตพืช', 'เกษตรศาสตร์ กลุ่มวิชาเทคโนโลยีการเพาะเลี้ยงสัตว์น้ำ',
                'นวัตกรรมอาหารและแปรรูป', 'สัตวศาสตร์'
            ],
            'คณะพยาบาลศาสตร์' => [
                'พยาบาลศาสตร์'
            ],
            'บัณฑิตวิทยาลัย' => [
                'หลักสูตรและการจัดการเรียนรู้', 'การบริหารการศึกษา', 'วิจัยและประเมินผล',
                'ดนตรีศึกษา'
            ]
        ];

        return view('portfolios.search', compact(
            'portfolios', 
            'query', 
            'faculty', 
            'major', 
            'rating',
            'faculties', 
            'facultyMajors'
        ));
    }
}
