<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonalInfoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_create_personal_info()
    {
        $user = User::factory()->create();
        
        $personalInfo = PersonalInfo::create([
            'user_id' => $user->id,
            'title_th' => 'โปรเจคทดสอบ',
            'title_en' => 'Test Project',
            'description' => 'คำอธิบายโปรเจคทดสอบ',
            'faculty' => 'คณะวิศวกรรมศาสตร์',
            'major' => 'วิศวกรรมคอมพิวเตอร์',
            'year' => 2024,
            'semester' => 1,
            'gpa' => 3.5,
            'subject_gpa' => 3.8,
        ]);

        $this->assertInstanceOf(PersonalInfo::class, $personalInfo);
        $this->assertEquals('โปรเจคทดสอบ', $personalInfo->title_th);
        $this->assertEquals('Test Project', $personalInfo->title_en);
        $this->assertEquals($user->id, $personalInfo->user_id);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $personalInfo = PersonalInfo::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $personalInfo->user);
        $this->assertEquals($user->id, $personalInfo->user->id);
    }

    /** @test */
    public function it_can_check_approval_status()
    {
        $personalInfo = PersonalInfo::factory()->create(['is_approved' => true]);
        
        $this->assertTrue($personalInfo->is_approved);
        $this->assertTrue($personalInfo->isApproved());
    }

    /** @test */
    public function it_can_check_pending_status()
    {
        $personalInfo = PersonalInfo::factory()->create(['is_approved' => null]);
        
        $this->assertNull($personalInfo->is_approved);
        $this->assertTrue($personalInfo->isPending());
    }

    /** @test */
    public function it_can_check_rejected_status()
    {
        $personalInfo = PersonalInfo::factory()->create(['is_approved' => false]);
        
        $this->assertFalse($personalInfo->is_approved);
        $this->assertTrue($personalInfo->isRejected());
    }

    /** @test */
    public function it_can_get_approval_status_text()
    {
        $approved = PersonalInfo::factory()->create(['is_approved' => true]);
        $pending = PersonalInfo::factory()->create(['is_approved' => null]);
        $rejected = PersonalInfo::factory()->create(['is_approved' => false]);

        $this->assertEquals('อนุมัติแล้ว', $approved->getApprovalStatusText());
        $this->assertEquals('รอการอนุมัติ', $pending->getApprovalStatusText());
        $this->assertEquals('ไม่อนุมัติ', $rejected->getApprovalStatusText());
    }

    /** @test */
    public function it_can_get_approval_status_class()
    {
        $approved = PersonalInfo::factory()->create(['is_approved' => true]);
        $pending = PersonalInfo::factory()->create(['is_approved' => null]);
        $rejected = PersonalInfo::factory()->create(['is_approved' => false]);

        $this->assertEquals('success', $approved->getApprovalStatusClass());
        $this->assertEquals('warning', $pending->getApprovalStatusClass());
        $this->assertEquals('danger', $rejected->getApprovalStatusClass());
    }

    /** @test */
    public function it_can_scope_approved_portfolios()
    {
        PersonalInfo::factory()->create(['is_approved' => true]);
        PersonalInfo::factory()->create(['is_approved' => false]);
        PersonalInfo::factory()->create(['is_approved' => null]);

        $approvedCount = PersonalInfo::approved()->count();
        $this->assertEquals(1, $approvedCount);
    }

    /** @test */
    public function it_can_scope_pending_portfolios()
    {
        PersonalInfo::factory()->create(['is_approved' => true]);
        PersonalInfo::factory()->create(['is_approved' => false]);
        PersonalInfo::factory()->create(['is_approved' => null]);

        $pendingCount = PersonalInfo::pending()->count();
        $this->assertEquals(1, $pendingCount);
    }

    /** @test */
    public function it_can_scope_rejected_portfolios()
    {
        PersonalInfo::factory()->create(['is_approved' => true]);
        PersonalInfo::factory()->create(['is_approved' => false]);
        PersonalInfo::factory()->create(['is_approved' => null]);

        $rejectedCount = PersonalInfo::rejected()->count();
        $this->assertEquals(1, $rejectedCount);
    }

    /** @test */
    public function it_can_search_by_title()
    {
        PersonalInfo::factory()->create(['title_th' => 'โปรเจคทดสอบ']);
        PersonalInfo::factory()->create(['title_th' => 'โปรเจคอื่น']);

        $results = PersonalInfo::search('ทดสอบ')->get();
        $this->assertEquals(1, $results->count());
        $this->assertEquals('โปรเจคทดสอบ', $results->first()->title_th);
    }

    /** @test */
    public function it_can_filter_by_faculty()
    {
        PersonalInfo::factory()->create(['faculty' => 'คณะวิศวกรรมศาสตร์']);
        PersonalInfo::factory()->create(['faculty' => 'คณะวิทยาศาสตร์']);

        $results = PersonalInfo::byFaculty('คณะวิศวกรรมศาสตร์')->get();
        $this->assertEquals(1, $results->count());
        $this->assertEquals('คณะวิศวกรรมศาสตร์', $results->first()->faculty);
    }

    /** @test */
    public function it_can_filter_by_major()
    {
        PersonalInfo::factory()->create(['major' => 'วิศวกรรมคอมพิวเตอร์']);
        PersonalInfo::factory()->create(['major' => 'วิศวกรรมไฟฟ้า']);

        $results = PersonalInfo::byMajor('วิศวกรรมคอมพิวเตอร์')->get();
        $this->assertEquals(1, $results->count());
        $this->assertEquals('วิศวกรรมคอมพิวเตอร์', $results->first()->major);
    }
}
