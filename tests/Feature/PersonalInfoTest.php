<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PersonalInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PersonalInfoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_create_personal_info()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/personal-info', [
            'title' => 'นาย',
            'first_name' => 'ทดสอบ',
            'last_name' => 'ระบบ',
            'age' => 25,
            'gender' => 'ชาย',
            'faculty' => 'คณะวิทยาศาสตร์',
            'major' => 'วิทยาการคอมพิวเตอร์',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('personal_info', [
            'user_id' => $user->id,
            'first_name' => 'ทดสอบ',
            'last_name' => 'ระบบ',
        ]);
    }

    public function test_user_can_edit_own_personal_info()
    {
        $user = User::factory()->create(['role' => 'user']);
        $personalInfo = PersonalInfo::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'เดิม',
        ]);

        $response = $this->actingAs($user)->put("/personal-info/{$personalInfo->id}/edit-my", [
            'title' => 'นาย',
            'first_name' => 'ใหม่',
            'last_name' => 'ระบบ',
            'age' => 25,
            'gender' => 'ชาย',
            'faculty' => 'คณะวิทยาศาสตร์',
            'major' => 'วิทยาการคอมพิวเตอร์',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('personal_info', [
            'id' => $personalInfo->id,
            'first_name' => 'ใหม่',
        ]);
    }

    public function test_user_cannot_edit_others_personal_info()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $personalInfo = PersonalInfo::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->put("/personal-info/{$personalInfo->id}/edit-my", [
            'title' => 'นาย',
            'first_name' => 'ใหม่',
            'last_name' => 'ระบบ',
            'age' => 25,
            'gender' => 'ชาย',
            'faculty' => 'คณะวิทยาศาสตร์',
            'major' => 'วิทยาการคอมพิวเตอร์',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_personal_info()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $personalInfo = PersonalInfo::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/personal-info/{$personalInfo->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('personal_info', [
            'id' => $personalInfo->id,
            'status' => 'approved',
        ]);
    }
}
