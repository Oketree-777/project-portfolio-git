<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use App\Models\Analytics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;

class PersonalInfoController extends Controller
{
    public function __construct()
    {
        // ไม่ใช้ auth middleware ใน constructor แล้ว
        // จะใช้เฉพาะใน method ที่ต้องการ
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $personalInfos = PersonalInfo::orderByDesc('created_at')->paginate(10);
        return view('personal-info.index', compact('personalInfos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // User และ Admin สามารถเข้าถึงได้ - ไม่ต้องตรวจสอบ admin
        $titles = ['นาย', 'นางสาว'];
        $genders = ['ชาย', 'หญิง'];
        
        // ระดับการศึกษา
        $educationLevels = [
            'มัธยมศึกษาตอนปลาย (4 ภาคเรียน)',
            'ประกาศนียบัตรวิชาชีพ (ปวช.) (4 ภาคเรียน)',
            'เทียบเท่าระดับมัธยมศึกษาตอนปลาย'
        ];
        
        // แผนการเรียน
        $studyPlans = [
            'วิทยาศาสตร์-คณิตศาสตร์',
            'ศิลป์คำนวณ',
            'ศิลป์ภาษา',
            'ศิลป์ทั่วไป',
            'อาชีวศึกษา'
        ];
        
        // จังหวัด
        $provinces = [
            'กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร',
            'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท',
            'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง',
            'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม',
            'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส',
            'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์',
            'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พังงา', 'พัทลุง',
            'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่',
            'พะเยา', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน',
            'ยะลา', 'ยโสธร', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง',
            'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย',
            'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ',
            'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี',
            'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย',
            'หนองบัวลำภู', 'อ่างทอง', 'อุดรธานี', 'อุทัยธานี', 'อุตรดิตถ์',
            'อุบลราชธานี', 'อำนาจเจริญ'
        ];
        
        // กลุ่มสาระการเรียนรู้
        $subjectGroups = [
            'คณิตศาสตร์',
            'วิทยาศาสตร์',
            'สุขศึกษาและพลศึกษา',
            'ภาษาต่างประเทศ'
        ];
        
        // คณะและสาขา (ข้อมูลจากมหาวิทยาลัยราชภัฏบุรีรัมย์)
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
        
        // สาขาตามคณะ (ข้อมูลจากมหาวิทยาลัยราชภัฏบุรีรัมย์)
        $facultyMajors = [
            'คณะครุศาสตร์' => [
                'นาฏศิลป์',
                'คณิตศาสตร์',
                'การศึกษาปฐมวัย',
                'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา',
                'สังคมศึกษา',
                'ภาษาอังกฤษ',
                'วิทยาศาสตร์ทั่วไป',
                'ภาษาไทย',
                'ศิลปศึกษา',
                'ดนตรีศึกษา',
                'พลศึกษา',
                'ฟิสิกส์'
            ],
            'คณะวิทยาศาสตร์' => [
                'ภูมิสารสนเทศ',
                'เคมี',
                'วิทยาศาสตร์สิ่งแวดล้อม',
                'สาธารณสุขศาสตร์',
                'สถิติประยุกต์และวิทยาการสารสนเทศ',
                'ชีววิทยา',
                'เทคโนโลยีสารสนเทศ',
                'วิทยาการคอมพิวเตอร์',
                'คณิตศาสตร์',
                'วิทยาศาสตร์การกีฬา',
                'การออกแบบแฟชั่นและธุรกิจสิ่งทอ'
            ],
            'คณะมนุษยศาสตร์และสังคมศาสตร์' => [
                'การพัฒนาสังคม',
                'ภาษาไทย',
                'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์',
                'ภาษาอังกฤษ',
                'ภาษาอังกฤษธุรกิจ',
                'ดนตรีสากล',
                'ศิลปะดิจิทัล',
                'รัฐประศาสนศาสตร์',
                'นิติศาสตร์'
            ],
            'คณะวิทยาการจัดการ' => [
                'การบัญชี',
                'การสื่อสารมวลชน',
                'การท่องเที่ยวและการโรงแรม',
                'เศรษฐศาสตร์',
                'การเงินและการธนาคาร',
                'การจัดการ',
                'การตลาด',
                'การบริหารทรัพยากรมนุษย์',
                'คอมพิวเตอร์ธุรกิจ'
            ],
            'คณะเทคโนโลยีอุตสาหกรรม' => [
                'ศิลปะและการออกแบบ',
                'เทคโนโลยีสถาปัตยกรรม',
                'วิศวกรรมการจัดการอุตสาหกรรม',
                'เทคโนโลยีวิศวกรรมโยธา',
                'เทคโนโลยีวิศวกรรมไฟฟ้า',
                'เทคโนโลยีเซรามิกส์และการออกแบบ',
                'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์'
            ],
            'คณะเทคโนโลยีการเกษตร' => [
                'เกษตรศาสตร์ กลุ่มวิชานวัตกรรมการผลิตพืช',
                'เกษตรศาสตร์ กลุ่มวิชาเทคโนโลยีการเพาะเลี้ยงสัตว์น้ำ',
                'นวัตกรรมอาหารและแปรรูป',
                'สัตวศาสตร์'
            ],
            'คณะพยาบาลศาสตร์' => [
                'พยาบาลศาสตร์'
            ],
            'บัณฑิตวิทยาลัย' => [
                'หลักสูตรและการจัดการเรียนรู้',
                'การบริหารการศึกษา',
                'วิจัยและประเมินผล',
                'ดนตรีศึกษา'
            ]
        ];
        
        // สาขาทั้งหมด (สำหรับ dropdown เดิม)
        $majors = [];
        foreach ($facultyMajors as $facultyMajorsList) {
            $majors = array_merge($majors, $facultyMajorsList);
        }

        return view('personal-info.create', compact(
            'titles', 'genders', 'educationLevels', 'studyPlans', 'provinces', 
            'subjectGroups', 'faculties', 'majors', 'facultyMajors'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            Log::warning('Unauthorized attempt to create personal info', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return redirect()->route('login');
        }
        
        // User และ Admin สามารถเข้าถึงได้ - ไม่ต้องตรวจสอบ admin
        $request->validate([
            'title' => 'required|in:นาย,นางสาว',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'first_name_en' => 'nullable|string|max:100',
            'last_name_en' => 'nullable|string|max:100',
            'title_en' => 'nullable|in:MR.,Miss',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:ชาย,หญิง',
            'faculty' => 'required|string|max:200',
            'major' => 'required|string|max:200',
            'education_level' => 'nullable|string|max:200',
            'study_plan' => 'nullable|string|max:200',
            'institution' => 'nullable|string|max:200',
            'province' => 'nullable|string|max:100',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'subject_groups' => 'nullable|array',
            'subject_groups.*' => 'string|max:100',
            'national_id' => 'nullable|array',
            'national_id.*' => 'string|size:1|regex:/^[0-9]$/',
            'house_number' => 'nullable|string|max:50',
            'village_no' => 'nullable|string|max:50',
            'road' => 'nullable|string|max:100',
            'sub_district' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province_address' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:10',
            'major_code' => 'nullable|string|max:50',
            'major_name' => 'nullable|string|max:200',
            'program' => 'nullable|string|max:200',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'portfolio_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // หน้าปก Portfolio (รูปภาพเท่านั้น)
            'portfolio_file' => 'nullable|file|mimes:pdf|max:10240', // ไฟล์ PDF ประกอบ (PDF เท่านั้น)
        ], [
            'title.required' => 'กรุณาเลือกคำนำหน้า',
            'title.in' => 'คำนำหน้าต้องเป็น นาย หรือ นางสาว',
            'first_name.required' => 'กรุณาป้อนชื่อ',
            'first_name.max' => 'ชื่อห้ามเกิน 100 ตัวอักษร',
            'last_name.required' => 'กรุณาป้อนนามสกุล',
            'last_name.max' => 'นามสกุลห้ามเกิน 100 ตัวอักษร',
            'age.required' => 'กรุณาป้อนอายุ',
            'age.integer' => 'อายุต้องเป็นตัวเลข',
            'age.min' => 'อายุต้องมากกว่า 0',
            'age.max' => 'อายุต้องไม่เกิน 120',
            'gender.required' => 'กรุณาเลือกเพศ',
            'gender.in' => 'เพศต้องเป็น ชาย หรือ หญิง',
            'faculty.required' => 'กรุณาเลือกคณะ',
            'faculty.max' => 'ชื่อคณะห้ามเกิน 200 ตัวอักษร',
            'major.required' => 'กรุณาเลือกสาขา',
            'major.max' => 'ชื่อสาขาห้ามเกิน 200 ตัวอักษร',
            'gpa.numeric' => 'GPA ต้องเป็นตัวเลข',
            'gpa.min' => 'GPA ต้องไม่น้อยกว่า 0',
            'gpa.max' => 'GPA ต้องไม่เกิน 4',
            'national_id.*.regex' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 0-9 เท่านั้น',
            'phone.max' => 'เบอร์โทรศัพท์ห้ามเกิน 10 หลัก',
            'postal_code.max' => 'รหัสไปรษณีย์ห้ามเกิน 5 หลัก',
            'photo.image' => 'ไฟล์รูปภาพไม่ถูกต้อง',
            'photo.mimes' => 'รองรับเฉพาะไฟล์ jpeg, png, jpg, gif',
            'photo.max' => 'ขนาดไฟล์รูปภาพห้ามเกิน 2MB',
            'portfolio_cover.image' => 'ไฟล์หน้าปก Portfolio ต้องเป็นรูปภาพ',
            'portfolio_cover.mimes' => 'รองรับเฉพาะไฟล์ jpeg, png, jpg, gif สำหรับหน้าปก Portfolio',
            'portfolio_cover.max' => 'ขนาดไฟล์หน้าปก Portfolio ห้ามเกิน 2MB',
            'portfolio_file.mimes' => 'รองรับเฉพาะไฟล์ PDF เท่านั้น',
            'portfolio_file.max' => 'ขนาดไฟล์ PDF Portfolio ห้ามเกิน 10MB',
        ]);

        try {
            // จัดการข้อมูล national_id จาก array เป็น string
            $data = $request->all();
            if (isset($data['national_id']) && is_array($data['national_id'])) {
                $data['national_id'] = implode('', $data['national_id']);
            }

            // จัดการรูปภาพ
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('personal-photos', 'public');
                $data['photo'] = $photoPath;
            }

            // จัดการหน้าปก Portfolio (รูปภาพ)
            if ($request->hasFile('portfolio_cover')) {
                $portfolioCoverPath = $request->file('portfolio_cover')->store('portfolio-covers', 'public');
                $data['portfolio_cover'] = $portfolioCoverPath;
            }

            // จัดการไฟล์ PDF ประกอบ Portfolio
            if ($request->hasFile('portfolio_file')) {
                $portfolioPath = $request->file('portfolio_file')->store('portfolio-files', 'public');
                $data['portfolio_file'] = $portfolioPath;
                $data['portfolio_filename'] = $request->file('portfolio_file')->getClientOriginalName();
            }

            $data['user_id'] = auth()->id();
            $data['status'] = 'pending';

            $personalInfo = PersonalInfo::create($data);

            // สร้างการแจ้งเตือนสำหรับ Admin ทั้งหมด
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::createNewPortfolioNotification(
                    $admin->id,
                    $data['title'] . ' ' . $data['first_name'] . ' ' . $data['last_name'],
                    $data['first_name'] . ' ' . $data['last_name'],
                    $data['faculty'],
                    $data['major'],
                    $personalInfo->id
                );
            }

            Log::info('Personal info created successfully', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'personal_info_id' => $personalInfo->id,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'เพิ่มข้อมูลส่วนตัวสำเร็จ');
        } catch (\Exception $e) {
            Log::error('Failed to create personal info', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'data' => $request->except(['photo']),
            ]);

            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่าผลงานได้รับการอนุมัติหรือไม่
        if ($personalInfo->status !== 'approved') {
            // ถ้าไม่ได้ login หรือไม่ใช่ admin ให้แสดง error
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(404, 'ผลงานไม่พบหรือยังไม่ได้รับการอนุมัติ');
            }
        }

        // Track portfolio view
        Analytics::trackPortfolioView($personalInfo->id, [
            'portfolio_title' => $personalInfo->first_name . ' ' . $personalInfo->last_name,
            'faculty' => $personalInfo->faculty,
            'major' => $personalInfo->major
        ]);

        // Increment views count
        $personalInfo->incrementViews();

        // Load ratings with user data
        $personalInfo->load('ratings.user');
        
        return view('personal-info.show', compact('personalInfo'));
    }

    /**
     * Download portfolio file
     */
    public function download(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่าผลงานได้รับการอนุมัติหรือไม่
        if ($personalInfo->status !== 'approved') {
            // ถ้าไม่ได้ login หรือไม่ใช่ admin ให้แสดง error
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(404, 'ผลงานไม่พบหรือยังไม่ได้รับการอนุมัติ');
            }
        }

        // ตรวจสอบว่ามีไฟล์หรือไม่
        if (!$personalInfo->portfolio_file || !Storage::disk('public')->exists($personalInfo->portfolio_file)) {
            abort(404, 'ไฟล์ไม่พบ');
        }

        // Track download
        Analytics::trackDownload($personalInfo->id, [
            'portfolio_title' => $personalInfo->first_name . ' ' . $personalInfo->last_name,
            'faculty' => $personalInfo->faculty,
            'major' => $personalInfo->major,
            'filename' => $personalInfo->portfolio_filename
        ]);

        // Return file download
        $filename = $personalInfo->portfolio_filename ?: basename($personalInfo->portfolio_file);
        return Storage::disk('public')->download($personalInfo->portfolio_file, $filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $titles = ['นาย', 'นางสาว'];
        $genders = ['ชาย', 'หญิง'];
        
        // ระดับการศึกษา
        $educationLevels = [
            'มัธยมศึกษาตอนปลาย (4 ภาคเรียน)',
            'ประกาศนียบัตรวิชาชีพ (ปวช.) (4 ภาคเรียน)',
            'เทียบเท่าระดับมัธยมศึกษาตอนปลาย'
        ];
        
        // แผนการเรียน
        $studyPlans = [
            'วิทยาศาสตร์-คณิตศาสตร์',
            'ศิลป์คำนวณ',
            'ศิลป์ภาษา',
            'ศิลป์ทั่วไป',
            'อาชีวศึกษา'
        ];
        
        // จังหวัด
        $provinces = [
            'กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร',
            'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท',
            'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง',
            'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม',
            'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส',
            'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์',
            'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พังงา', 'พัทลุง',
            'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่',
            'พะเยา', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน',
            'ยะลา', 'ยโสธร', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง',
            'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย',
            'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ',
            'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี',
            'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย',
            'หนองบัวลำภู', 'อ่างทอง', 'อุดรธานี', 'อุทัยธานี', 'อุตรดิตถ์',
            'อุบลราชธานี', 'อำนาจเจริญ'
        ];
        
        // กลุ่มสาระการเรียนรู้
        $subjectGroups = [
            'คณิตศาสตร์',
            'วิทยาศาสตร์',
            'สุขศึกษาและพลศึกษา',
            'ภาษาต่างประเทศ'
        ];
        
        // คณะและสาขา (ข้อมูลจากมหาวิทยาลัยราชภัฏบุรีรัมย์)
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
        
        // สาขาตามคณะ (ข้อมูลจากมหาวิทยาลัยราชภัฏบุรีรัมย์)
        $facultyMajors = [
            'คณะครุศาสตร์' => [
                'นาฏศิลป์',
                'คณิตศาสตร์',
                'การศึกษาปฐมวัย',
                'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา',
                'สังคมศึกษา',
                'ภาษาอังกฤษ',
                'วิทยาศาสตร์ทั่วไป',
                'ภาษาไทย',
                'ศิลปศึกษา',
                'ดนตรีศึกษา',
                'พลศึกษา',
                'ฟิสิกส์'
            ],
            'คณะวิทยาศาสตร์' => [
                'ภูมิสารสนเทศ',
                'เคมี',
                'วิทยาศาสตร์สิ่งแวดล้อม',
                'สาธารณสุขศาสตร์',
                'สถิติประยุกต์และวิทยาการสารสนเทศ',
                'ชีววิทยา',
                'เทคโนโลยีสารสนเทศ',
                'วิทยาการคอมพิวเตอร์',
                'คณิตศาสตร์',
                'วิทยาศาสตร์การกีฬา',
                'การออกแบบแฟชั่นและธุรกิจสิ่งทอ'
            ],
            'คณะมนุษยศาสตร์และสังคมศาสตร์' => [
                'การพัฒนาสังคม',
                'ภาษาไทย',
                'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์',
                'ภาษาอังกฤษ',
                'ภาษาอังกฤษธุรกิจ',
                'ดนตรีสากล',
                'ศิลปะดิจิทัล',
                'รัฐประศาสนศาสตร์',
                'นิติศาสตร์'
            ],
            'คณะวิทยาการจัดการ' => [
                'การบัญชี',
                'การสื่อสารมวลชน',
                'การท่องเที่ยวและการโรงแรม',
                'เศรษฐศาสตร์',
                'การเงินและการธนาคาร',
                'การจัดการ',
                'การตลาด',
                'การบริหารทรัพยากรมนุษย์',
                'คอมพิวเตอร์ธุรกิจ'
            ],
            'คณะเทคโนโลยีอุตสาหกรรม' => [
                'ศิลปะและการออกแบบ',
                'เทคโนโลยีสถาปัตยกรรม',
                'วิศวกรรมการจัดการอุตสาหกรรม',
                'เทคโนโลยีวิศวกรรมโยธา',
                'เทคโนโลยีวิศวกรรมไฟฟ้า',
                'เทคโนโลยีเซรามิกส์และการออกแบบ',
                'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์'
            ],
            'คณะเทคโนโลยีการเกษตร' => [
                'เกษตรศาสตร์ กลุ่มวิชานวัตกรรมการผลิตพืช',
                'เกษตรศาสตร์ กลุ่มวิชาเทคโนโลยีการเพาะเลี้ยงสัตว์น้ำ',
                'นวัตกรรมอาหารและแปรรูป',
                'สัตวศาสตร์'
            ],
            'คณะพยาบาลศาสตร์' => [
                'พยาบาลศาสตร์'
            ],
            'บัณฑิตวิทยาลัย' => [
                'หลักสูตรและการจัดการเรียนรู้',
                'การบริหารการศึกษา',
                'วิจัยและประเมินผล',
                'ดนตรีศึกษา'
            ]
        ];
        
        // สาขาทั้งหมด (สำหรับ dropdown เดิม)
        $majors = [];
        foreach ($facultyMajors as $facultyMajorsList) {
            $majors = array_merge($majors, $facultyMajorsList);
        }

        return view('personal-info.edit', compact('personalInfo', 'titles', 'genders', 'faculties', 'majors', 'facultyMajors', 'educationLevels', 'studyPlans', 'provinces', 'subjectGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $request->validate([
            'title' => 'required|in:นาย,นางสาว',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'first_name_en' => 'nullable|string|max:100',
            'last_name_en' => 'nullable|string|max:100',
            'title_en' => 'nullable|in:MR.,Miss',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:ชาย,หญิง',
            'faculty' => 'required|string|max:200',
            'major' => 'required|string|max:200',
            'education_level' => 'nullable|string|max:200',
            'study_plan' => 'nullable|string|max:200',
            'institution' => 'nullable|string|max:200',
            'province' => 'nullable|string|max:100',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'subject_groups' => 'nullable|array',
            'subject_groups.*' => 'string|max:100',
            'national_id' => 'nullable|array',
            'national_id.*' => 'string|size:1|regex:/^[0-9]$/',
            'house_number' => 'nullable|string|max:50',
            'village_no' => 'nullable|string|max:50',
            'road' => 'nullable|string|max:100',
            'sub_district' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province_address' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:10',
            'major_code' => 'nullable|string|max:50',
            'major_name' => 'nullable|string|max:200',
            'program' => 'nullable|string|max:200',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'กรุณาเลือกคำนำหน้า',
            'title.in' => 'คำนำหน้าต้องเป็น นาย หรือ นางสาว',
            'first_name.required' => 'กรุณาป้อนชื่อ',
            'first_name.max' => 'ชื่อห้ามเกิน 100 ตัวอักษร',
            'last_name.required' => 'กรุณาป้อนนามสกุล',
            'last_name.max' => 'นามสกุลห้ามเกิน 100 ตัวอักษร',
            'age.required' => 'กรุณาป้อนอายุ',
            'age.integer' => 'อายุต้องเป็นตัวเลข',
            'age.min' => 'อายุต้องมากกว่า 0',
            'age.max' => 'อายุต้องไม่เกิน 120',
            'gender.required' => 'กรุณาเลือกเพศ',
            'gender.in' => 'เพศต้องเป็น ชาย หรือ หญิง',
            'faculty.required' => 'กรุณาเลือกคณะ',
            'faculty.max' => 'ชื่อคณะห้ามเกิน 200 ตัวอักษร',
            'major.required' => 'กรุณาเลือกสาขา',
            'major.max' => 'ชื่อสาขาห้ามเกิน 200 ตัวอักษร',
            'gpa.numeric' => 'GPA ต้องเป็นตัวเลข',
            'gpa.min' => 'GPA ต้องไม่น้อยกว่า 0',
            'gpa.max' => 'GPA ต้องไม่เกิน 4',
            'national_id.*.regex' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 0-9 เท่านั้น',
            'phone.max' => 'เบอร์โทรศัพท์ห้ามเกิน 10 หลัก',
            'postal_code.max' => 'รหัสไปรษณีย์ห้ามเกิน 5 หลัก',
            'photo.image' => 'ไฟล์รูปภาพไม่ถูกต้อง',
            'photo.mimes' => 'รองรับเฉพาะไฟล์ jpeg, png, jpg, gif',
            'photo.max' => 'ขนาดไฟล์รูปภาพห้ามเกิน 2MB',
        ]);

        // จัดการข้อมูล national_id จาก array เป็น string
        $data = $request->all();
        if (isset($data['national_id']) && is_array($data['national_id'])) {
            $data['national_id'] = implode('', $data['national_id']);
        }

        // จัดการรูปภาพ
        if ($request->hasFile('photo')) {
            // ลบรูปเก่า
            if ($personalInfo->photo) {
                \Storage::disk('public')->delete($personalInfo->photo);
            }
            $photoPath = $request->file('photo')->store('personal-photos', 'public');
            $data['photo'] = $photoPath;
        }

        $personalInfo->update($data);

        return redirect()->route('personal-info.index')
            ->with('success', 'อัพเดทข้อมูลส่วนตัวสำเร็จ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $personalInfo->delete();

        return redirect()->route('personal-info.index')
            ->with('success', 'ลบข้อมูลส่วนตัวสำเร็จ');
    }

    /**
     * Approval methods
     */
    public function approve(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $personalInfo->approve(auth()->id());

        // สร้างการแจ้งเตือน
        \App\Models\Notification::createApprovalNotification(
            $personalInfo->user_id,
            $personalInfo->title . ' ' . $personalInfo->first_name . ' ' . $personalInfo->last_name,
            $personalInfo->id
        );

        return redirect()->back()
            ->with('success', 'อนุมัติผลงาน Portfolio สำเร็จ');
    }

    public function reject(Request $request, PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ], [
            'rejection_reason.required' => 'กรุณาระบุเหตุผลในการไม่อนุมัติ',
            'rejection_reason.max' => 'เหตุผลห้ามเกิน 500 ตัวอักษร'
        ]);

        $personalInfo->reject(auth()->id(), $request->rejection_reason);

        // สร้างการแจ้งเตือน
        \App\Models\Notification::createRejectionNotification(
            $personalInfo->user_id,
            $personalInfo->title . ' ' . $personalInfo->first_name . ' ' . $personalInfo->last_name,
            $request->rejection_reason
        );

        return redirect()->back()
            ->with('success', 'ไม่อนุมัติผลงาน Portfolio สำเร็จ');
    }

    public function pending()
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $pendingInfos = PersonalInfo::where('status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('personal-info.pending', compact('pendingInfos'));
    }

    public function approved()
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $approvedInfos = PersonalInfo::where('status', 'approved')
            ->orderByDesc('approved_at')
            ->paginate(10);
        
        return view('personal-info.approved', compact('approvedInfos'));
    }

    public function rejected()
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $rejectedInfos = PersonalInfo::where('status', 'rejected')
            ->orderByDesc('rejected_at')
            ->paginate(10);
        
        return view('personal-info.rejected', compact('rejectedInfos'));
    }

    public function cancelApproval(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        $personalInfo->cancelApproval(auth()->id());

        return redirect()->back()
            ->with('success', 'ยกเลิกการอนุมัติผลงาน Portfolio สำเร็จ');
    }

    /**
     * User แก้ไขผลงานของตัวเอง
     */
    public function editMy(PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // ตรวจสอบว่าเป็นผลงานของ User นี้หรือไม่
        if ($personalInfo->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขผลงานนี้');
        }

        $titles = ['นาย', 'นางสาว'];
        $genders = ['ชาย', 'หญิง'];
        
        // ระดับการศึกษา
        $educationLevels = [
            'มัธยมศึกษาตอนปลาย (4 ภาคเรียน)',
            'ประกาศนียบัตรวิชาชีพ (ปวช.) (4 ภาคเรียน)',
            'เทียบเท่าระดับมัธยมศึกษาตอนปลาย'
        ];
        
        // แผนการเรียน
        $studyPlans = [
            'วิทยาศาสตร์-คณิตศาสตร์',
            'ศิลป์คำนวณ',
            'ศิลป์ภาษา',
            'ศิลป์ทั่วไป',
            'อาชีวศึกษา'
        ];
        
        // จังหวัด
        $provinces = [
            'กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร',
            'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท',
            'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง',
            'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม',
            'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส',
            'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์',
            'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พังงา', 'พัทลุง',
            'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่',
            'พะเยา', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน',
            'ยะลา', 'ยโสธร', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง',
            'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย',
            'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ',
            'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี',
            'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย',
            'หนองบัวลำภู', 'อ่างทอง', 'อุดรธานี', 'อุทัยธานี', 'อุตรดิตถ์',
            'อุบลราชธานี', 'อำนาจเจริญ'
        ];
        
        // กลุ่มสาระการเรียนรู้
        $subjectGroups = [
            'คณิตศาสตร์',
            'วิทยาศาสตร์',
            'สุขศึกษาและพลศึกษา',
            'ภาษาต่างประเทศ'
        ];
        
        // คณะและสาขา (ข้อมูลจากมหาวิทยาลัยราชภัฏบุรีรัมย์)
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
        
        // สาขาตามคณะ (ข้อมูลจากมหาวิทยาลัยราชภัฏบุรีรัมย์)
        $facultyMajors = [
            'คณะครุศาสตร์' => [
                'นาฏศิลป์',
                'คณิตศาสตร์',
                'การศึกษาปฐมวัย',
                'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา',
                'สังคมศึกษา',
                'ภาษาอังกฤษ',
                'วิทยาศาสตร์ทั่วไป',
                'ภาษาไทย',
                'ศิลปศึกษา',
                'ดนตรีศึกษา',
                'พลศึกษา',
                'ฟิสิกส์'
            ],
            'คณะวิทยาศาสตร์' => [
                'ภูมิสารสนเทศ',
                'เคมี',
                'วิทยาศาสตร์สิ่งแวดล้อม',
                'สาธารณสุขศาสตร์',
                'สถิติประยุกต์และวิทยาการสารสนเทศ',
                'ชีววิทยา',
                'เทคโนโลยีสารสนเทศ',
                'วิทยาการคอมพิวเตอร์',
                'คณิตศาสตร์',
                'วิทยาศาสตร์การกีฬา',
                'การออกแบบแฟชั่นและธุรกิจสิ่งทอ'
            ],
            'คณะมนุษยศาสตร์และสังคมศาสตร์' => [
                'การพัฒนาสังคม',
                'ภาษาไทย',
                'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์',
                'ภาษาอังกฤษ',
                'ภาษาอังกฤษธุรกิจ',
                'ดนตรีสากล',
                'ศิลปะดิจิทัล',
                'รัฐประศาสนศาสตร์',
                'นิติศาสตร์'
            ],
            'คณะวิทยาการจัดการ' => [
                'การบัญชี',
                'การสื่อสารมวลชน',
                'การท่องเที่ยวและการโรงแรม',
                'เศรษฐศาสตร์',
                'การเงินและการธนาคาร',
                'การจัดการ',
                'การตลาด',
                'การบริหารทรัพยากรมนุษย์',
                'คอมพิวเตอร์ธุรกิจ'
            ],
            'คณะเทคโนโลยีอุตสาหกรรม' => [
                'ศิลปะและการออกแบบ',
                'เทคโนโลยีสถาปัตยกรรม',
                'วิศวกรรมการจัดการอุตสาหกรรม',
                'เทคโนโลยีวิศวกรรมโยธา',
                'เทคโนโลยีวิศวกรรมไฟฟ้า',
                'เทคโนโลยีเซรามิกส์และการออกแบบ',
                'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์'
            ],
            'คณะเทคโนโลยีการเกษตร' => [
                'เกษตรศาสตร์ กลุ่มวิชานวัตกรรมการผลิตพืช',
                'เกษตรศาสตร์ กลุ่มวิชาเทคโนโลยีการเพาะเลี้ยงสัตว์น้ำ',
                'นวัตกรรมอาหารและแปรรูป',
                'สัตวศาสตร์'
            ],
            'คณะพยาบาลศาสตร์' => [
                'พยาบาลศาสตร์'
            ],
            'บัณฑิตวิทยาลัย' => [
                'หลักสูตรและการจัดการเรียนรู้',
                'การบริหารการศึกษา',
                'วิจัยและประเมินผล',
                'ดนตรีศึกษา'
            ]
        ];
        
        // สาขาทั้งหมด (สำหรับ dropdown เดิม)
        $majors = [];
        foreach ($facultyMajors as $facultyMajorsList) {
            $majors = array_merge($majors, $facultyMajorsList);
        }

        return view('personal-info.edit-my', compact(
            'personalInfo', 'titles', 'genders', 'educationLevels', 'studyPlans', 'provinces', 
            'subjectGroups', 'faculties', 'majors', 'facultyMajors'
        ));
    }

    public function updateMy(Request $request, PersonalInfo $personalInfo)
    {
        // ตรวจสอบว่า login แล้ว
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // ตรวจสอบว่าเป็นผลงานของ User นี้หรือไม่
        if ($personalInfo->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขผลงานนี้');
        }

        $request->validate([
            'title' => 'required|in:นาย,นางสาว',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'first_name_en' => 'nullable|string|max:100',
            'last_name_en' => 'nullable|string|max:100',
            'title_en' => 'nullable|in:MR.,Miss',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:ชาย,หญิง',
            'faculty' => 'required|string|max:200',
            'major' => 'required|string|max:200',
            'education_level' => 'nullable|string|max:200',
            'study_plan' => 'nullable|string|max:200',
            'institution' => 'nullable|string|max:200',
            'province' => 'nullable|string|max:100',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'subject_groups' => 'nullable|array',
            'subject_groups.*' => 'string|max:100',
            'national_id' => 'nullable|array',
            'national_id.*' => 'string|size:1|regex:/^[0-9]$/',
            'house_number' => 'nullable|string|max:50',
            'village_no' => 'nullable|string|max:50',
            'road' => 'nullable|string|max:100',
            'sub_district' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province_address' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:10',
            'major_code' => 'nullable|string|max:50',
            'major_name' => 'nullable|string|max:200',
            'program' => 'nullable|string|max:200',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'portfolio_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240', // รองรับไฟล์ Portfolio
        ], [
            'title.required' => 'กรุณาเลือกคำนำหน้า',
            'title.in' => 'คำนำหน้าต้องเป็น นาย หรือ นางสาว',
            'first_name.required' => 'กรุณาป้อนชื่อ',
            'first_name.max' => 'ชื่อห้ามเกิน 100 ตัวอักษร',
            'last_name.required' => 'กรุณาป้อนนามสกุล',
            'last_name.max' => 'นามสกุลห้ามเกิน 100 ตัวอักษร',
            'age.required' => 'กรุณาป้อนอายุ',
            'age.integer' => 'อายุต้องเป็นตัวเลข',
            'age.min' => 'อายุต้องมากกว่า 0',
            'age.max' => 'อายุต้องไม่เกิน 120',
            'gender.required' => 'กรุณาเลือกเพศ',
            'gender.in' => 'เพศต้องเป็น ชาย หรือ หญิง',
            'faculty.required' => 'กรุณาเลือกคณะ',
            'faculty.max' => 'ชื่อคณะห้ามเกิน 200 ตัวอักษร',
            'major.required' => 'กรุณาเลือกสาขา',
            'major.max' => 'ชื่อสาขาห้ามเกิน 200 ตัวอักษร',
            'gpa.numeric' => 'GPA ต้องเป็นตัวเลข',
            'gpa.min' => 'GPA ต้องไม่น้อยกว่า 0',
            'gpa.max' => 'GPA ต้องไม่เกิน 4',
            'national_id.*.regex' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 0-9 เท่านั้น',
            'phone.max' => 'เบอร์โทรศัพท์ห้ามเกิน 10 หลัก',
            'postal_code.max' => 'รหัสไปรษณีย์ห้ามเกิน 5 หลัก',
            'photo.image' => 'ไฟล์รูปภาพไม่ถูกต้อง',
            'photo.mimes' => 'รองรับเฉพาะไฟล์ jpeg, png, jpg, gif',
            'photo.max' => 'ขนาดไฟล์รูปภาพห้ามเกิน 2MB',
            'portfolio_file.mimes' => 'รองรับเฉพาะไฟล์ PDF, DOC, DOCX, JPG, JPEG, PNG, GIF',
            'portfolio_file.max' => 'ขนาดไฟล์ Portfolio ห้ามเกิน 10MB',
        ]);

        // จัดการข้อมูล national_id จาก array เป็น string
        $data = $request->all();
        if (isset($data['national_id']) && is_array($data['national_id'])) {
            $data['national_id'] = implode('', $data['national_id']);
        }

        // จัดการรูปภาพ
        if ($request->hasFile('photo')) {
            // ลบรูปเก่า
            if ($personalInfo->photo) {
                \Storage::disk('public')->delete($personalInfo->photo);
            }
            $photoPath = $request->file('photo')->store('personal-photos', 'public');
            $data['photo'] = $photoPath;
        }

        // จัดการหน้าปก Portfolio (รูปภาพ)
        if ($request->hasFile('portfolio_cover')) {
            // ลบไฟล์เก่า
            if ($personalInfo->portfolio_cover) {
                \Storage::disk('public')->delete($personalInfo->portfolio_cover);
            }
            $portfolioCoverPath = $request->file('portfolio_cover')->store('portfolio-covers', 'public');
            $data['portfolio_cover'] = $portfolioCoverPath;
        }

        // จัดการไฟล์ PDF ประกอบ Portfolio
        if ($request->hasFile('portfolio_file')) {
            // ลบไฟล์เก่า
            if ($personalInfo->portfolio_file) {
                \Storage::disk('public')->delete($personalInfo->portfolio_file);
            }
            $portfolioPath = $request->file('portfolio_file')->store('portfolio-files', 'public');
            $data['portfolio_file'] = $portfolioPath;
            $data['portfolio_filename'] = $request->file('portfolio_file')->getClientOriginalName();
        }

        $personalInfo->update($data);

        return redirect()->route('dashboard')
            ->with('success', 'อัพเดทข้อมูลส่วนตัวสำเร็จ');
    }

    /**
     * Get majors by faculty for dynamic dropdown
     */
    public function getMajorsByFaculty(Request $request)
    {
        $faculty = $request->input('faculty');
        
        $facultyMajors = [
            'คณะครุศาสตร์' => [
                'นาฏศิลป์',
                'คณิตศาสตร์',
                'การศึกษาปฐมวัย',
                'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา',
                'สังคมศึกษา',
                'ภาษาอังกฤษ',
                'วิทยาศาสตร์ทั่วไป',
                'ภาษาไทย',
                'ศิลปศึกษา',
                'ดนตรีศึกษา',
                'พลศึกษา',
                'ฟิสิกส์'
            ],
            'คณะวิทยาศาสตร์' => [
                'ภูมิสารสนเทศ',
                'เคมี',
                'วิทยาศาสตร์สิ่งแวดล้อม',
                'สาธารณสุขศาสตร์',
                'สถิติประยุกต์และวิทยาการสารสนเทศ',
                'ชีววิทยา',
                'เทคโนโลยีสารสนเทศ',
                'วิทยาการคอมพิวเตอร์',
                'คณิตศาสตร์',
                'วิทยาศาสตร์การกีฬา',
                'การออกแบบแฟชั่นและธุรกิจสิ่งทอ'
            ],
            'คณะมนุษยศาสตร์และสังคมศาสตร์' => [
                'การพัฒนาสังคม',
                'ภาษาไทย',
                'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์',
                'ภาษาอังกฤษ',
                'ภาษาอังกฤษธุรกิจ',
                'ดนตรีสากล',
                'ศิลปะดิจิทัล',
                'รัฐประศาสนศาสตร์',
                'นิติศาสตร์'
            ],
            'คณะวิทยาการจัดการ' => [
                'การบัญชี',
                'การสื่อสารมวลชน',
                'การท่องเที่ยวและการโรงแรม',
                'เศรษฐศาสตร์',
                'การเงินและการธนาคาร',
                'การจัดการ',
                'การตลาด',
                'การบริหารทรัพยากรมนุษย์',
                'คอมพิวเตอร์ธุรกิจ'
            ],
            'คณะเทคโนโลยีอุตสาหกรรม' => [
                'ศิลปะและการออกแบบ',
                'เทคโนโลยีสถาปัตยกรรม',
                'วิศวกรรมการจัดการอุตสาหกรรม',
                'เทคโนโลยีวิศวกรรมโยธา',
                'เทคโนโลยีวิศวกรรมไฟฟ้า',
                'เทคโนโลยีเซรามิกส์และการออกแบบ',
                'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์'
            ],
            'คณะเทคโนโลยีการเกษตร' => [
                'เกษตรศาสตร์ กลุ่มวิชานวัตกรรมการผลิตพืช',
                'เกษตรศาสตร์ กลุ่มวิชาเทคโนโลยีการเพาะเลี้ยงสัตว์น้ำ',
                'นวัตกรรมอาหารและแปรรูป',
                'สัตวศาสตร์'
            ],
            'คณะพยาบาลศาสตร์' => [
                'พยาบาลศาสตร์'
            ],
            'บัณฑิตวิทยาลัย' => [
                'หลักสูตรและการจัดการเรียนรู้',
                'การบริหารการศึกษา',
                'วิจัยและประเมินผล',
                'ดนตรีศึกษา'
            ]
        ];
        
        $majors = $facultyMajors[$faculty] ?? [];
        
        return response()->json($majors);
    }

    /**
     * Show export form
     */
    public function showExportForm()
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return view('personal-info.export-form');
    }

    /**
     * Preview export data
     */
    public function previewExport(Request $request)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Access denied. Admin privileges required.']);
        }

        $query = PersonalInfo::query();

        // Debug: Log request parameters
        \Log::info('Preview Export Request:', $request->all());

        // Filter by date range
        $dateRange = $request->get('date_range', 'all');
        if ($dateRange !== 'all') {
            $query = $this->applyDateFilter($query, $dateRange, $request);
        }

        // Filter by faculty
        if ($request->filled('faculty') && $request->faculty !== '') {
            $query->where('faculty', $request->faculty);
            \Log::info('Filtering by faculty:', ['faculty' => $request->faculty]);
        }

        // Filter by major
        if ($request->filled('major') && $request->major !== '') {
            $query->where('major', $request->major);
            \Log::info('Filtering by major:', ['major' => $request->major]);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
            \Log::info('Filtering by status:', ['status' => $request->status]);
        }

        // Get data with pagination
        $perPage = $request->get('per_page', 10);
        if ($perPage === 'all') {
            $personalInfos = $query->orderByDesc('created_at')->get();
        } else {
            $personalInfos = $query->orderByDesc('created_at')->paginate($perPage);
        }

        // Debug: Log query results
        \Log::info('Query results count:', ['count' => $personalInfos->count()]);

        // Generate preview HTML
        $html = view('personal-info.preview-table', compact('personalInfos'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $personalInfos->count(),
            'debug' => [
                'filters' => $request->only(['date_range', 'faculty', 'major', 'status', 'per_page']),
                'query_count' => $personalInfos->count()
            ]
        ]);
    }

    /**
     * Export personal information to PDF with filters
     */
    public function exportToPdf(Request $request)
    {
        try {
            // Get filtered data
            $personalInfos = $this->getFilteredPersonalInfos($request);
            
            if ($personalInfos->isEmpty()) {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ตรงกับเงื่อนไขการกรอง');
            }

            // Generate PDF using Snappy
            $pdf = SnappyPdf::loadView('personal-info.pdf-simple', compact('personalInfos'));
            
            // Set PDF options
            $pdf->setOption('encoding', 'UTF-8');
            $pdf->setOption('page-size', 'A4');
            $pdf->setOption('margin-top', '15mm');
            $pdf->setOption('margin-right', '15mm');
            $pdf->setOption('margin-bottom', '15mm');
            $pdf->setOption('margin-left', '15mm');
            $pdf->setOption('enable-local-file-access', true);
            
            // Generate filename
            $filename = 'personal-info-' . now()->format('Y-m-d') . '.pdf';
            
            // Return PDF for download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการส่งออก PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get filtered personal information data
     */
    private function getFilteredPersonalInfos(Request $request)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $query = PersonalInfo::query();

        // Filter by date range
        $dateRange = $request->get('date_range', 'all');
        if ($dateRange !== 'all') {
            $query = $this->applyDateFilter($query, $dateRange, $request);
        }

        // Filter by faculty
        if ($request->filled('faculty') && $request->faculty !== '') {
            $query->where('faculty', $request->faculty);
        }

        // Filter by major
        if ($request->filled('major') && $request->major !== '') {
            $query->where('major', $request->major);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * Apply date filter to query
     */
    private function applyDateFilter($query, $dateRange, $request)
    {
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'quarter':
                $query->where('created_at', '>=', now()->subMonths(3));
                break;
            case 'year':
                $query->where('created_at', '>=', now()->subYear());
                break;
            case 'custom':
                if ($request->filled('start_date')) {
                    $query->where('created_at', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
                }
                break;
        }
        return $query;
    }

    /**
     * Generate filename based on filters
     */
    private function generateFilename($request)
    {
        $parts = ['personal-info'];
        
        if ($request->filled('date_range') && $request->date_range !== 'all') {
            $parts[] = $request->date_range;
        }
        
        if ($request->filled('faculty')) {
            $parts[] = str_replace(' ', '-', $request->faculty);
        }
        
        if ($request->filled('major')) {
            $parts[] = str_replace(' ', '-', $request->major);
        }
        
        if ($request->filled('status')) {
            $parts[] = $request->status;
        }
        
        $parts[] = now()->format('Y-m-d');
        
        return implode('-', $parts) . '.pdf';
    }
}
