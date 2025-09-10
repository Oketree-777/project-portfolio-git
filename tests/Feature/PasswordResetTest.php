<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PasswordResetCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function user_can_request_password_reset()
    {
        $user = User::factory()->create();

        $response = $this->post('/password/email', [
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        // ตรวจสอบว่าสร้างรหัสรีเซ็ต
        $this->assertDatabaseHas('password_reset_codes', [
            'email' => $user->email,
        ]);

        // ตรวจสอบว่าส่งอีเมล
        Mail::assertSent(PasswordResetMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function user_cannot_request_reset_with_invalid_email()
    {
        $response = $this->post('/password/email', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_can_reset_password_with_valid_code()
    {
        $user = User::factory()->create();
        $resetCode = PasswordResetCode::createForEmail($user->email);

        $response = $this->post('/password/reset', [
            'email' => $user->email,
            'code' => $resetCode->code,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');

        // ตรวจสอบว่ารหัสผ่านเปลี่ยน
        $this->assertTrue(
            \Hash::check('newpassword123', $user->fresh()->password)
        );

        // ตรวจสอบว่ารหัสถูกใช้แล้ว
        $this->assertTrue($resetCode->fresh()->used);
    }

    /** @test */
    public function user_cannot_reset_password_with_invalid_code()
    {
        $user = User::factory()->create();

        $response = $this->post('/password/reset', [
            'email' => $user->email,
            'code' => '000000',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['code']);
    }

    /** @test */
    public function user_cannot_reset_password_with_expired_code()
    {
        $user = User::factory()->create();
        $resetCode = PasswordResetCode::create([
            'email' => $user->email,
            'code' => '123456',
            'expires_at' => now()->subHour(), // หมดอายุแล้ว
        ]);

        $response = $this->post('/password/reset', [
            'email' => $user->email,
            'code' => $resetCode->code,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['code']);
    }

    /** @test */
    public function user_can_resend_reset_code()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/password/resend', [
            'email' => $user->email,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        // ตรวจสอบว่าสร้างรหัสใหม่
        $this->assertDatabaseHas('password_reset_codes', [
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function user_can_verify_reset_code()
    {
        $user = User::factory()->create();
        $resetCode = PasswordResetCode::createForEmail($user->email);

        $response = $this->postJson('/password/verify', [
            'email' => $user->email,
            'code' => $resetCode->code,
        ]);

        $response->assertJson([
            'success' => true,
        ]);
    }

    /** @test */
    public function user_cannot_verify_invalid_reset_code()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/password/verify', [
            'email' => $user->email,
            'code' => '000000',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $user = User::factory()->create();
        $resetCode = PasswordResetCode::createForEmail($user->email);

        $response = $this->post('/password/reset', [
            'email' => $user->email,
            'code' => $resetCode->code,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function password_must_be_at_least_8_characters()
    {
        $user = User::factory()->create();
        $resetCode = PasswordResetCode::createForEmail($user->email);

        $response = $this->post('/password/reset', [
            'email' => $user->email,
            'code' => $resetCode->code,
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
