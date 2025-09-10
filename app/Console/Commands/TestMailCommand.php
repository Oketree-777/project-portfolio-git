<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\PasswordResetCode;
use App\Mail\PasswordResetMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            // ใช้ email แรกจากฐานข้อมูล
            $user = User::first();
            if (!$user) {
                $this->error('No users found in database');
                return Command::FAILURE;
            }
            $email = $user->email;
        }

        $this->info("Testing email to: {$email}");

        try {
            // ทดสอบส่งอีเมลธรรมดา
            $this->info('Testing basic email...');
            Mail::raw('Test email from Laravel', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - ' . now());
            });
            $this->info('✓ Basic email sent successfully');

            // ทดสอบส่งอีเมลรีเซ็ตรหัสผ่าน
            $this->info('Testing password reset email...');
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error('User not found for email: ' . $email);
                return Command::FAILURE;
            }

            $resetCode = PasswordResetCode::createForEmail($email);
            
            Mail::to($email)->send(new PasswordResetMail(
                $resetCode->code,
                $user->name,
                $resetCode->expires_at->format('d/m/Y H:i')
            ));
            
            $this->info('✓ Password reset email sent successfully');
            $this->info("Reset code: {$resetCode->code}");
            $this->info("Expires at: {$resetCode->expires_at}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Email failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
