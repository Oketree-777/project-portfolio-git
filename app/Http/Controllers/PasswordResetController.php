<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetCode;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send password reset code
     */
    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.exists' => 'ไม่พบอีเมลนี้ในระบบ',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        // สร้างรหัสรีเซ็ต
        $resetCode = PasswordResetCode::createForEmail($email);

        try {
            // ส่งอีเมล
            Mail::to($email)->send(new PasswordResetMail(
                $resetCode->code,
                $user->name,
                $resetCode->expires_at->format('d/m/Y H:i')
            ));

            return back()->with('status', 'ส่งรหัสรีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว กรุณาตรวจสอบกล่องจดหมาย');
        } catch (\Exception $e) {
            // ลบรหัสที่สร้างไว้
            $resetCode->delete();
            
            return back()
                ->withErrors(['email' => 'ไม่สามารถส่งอีเมลได้ กรุณาลองใหม่อีกครั้ง'])
                ->withInput();
        }
    }

    /**
     * Show the password reset form
     */
    public function showResetForm(Request $request)
    {
        $email = $request->email;
        $code = $request->code;

        if (!$email || !$code) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'ลิงก์ไม่ถูกต้อง']);
        }

        // ตรวจสอบรหัส
        $resetCode = PasswordResetCode::findValid($email, $code);
        
        if (!$resetCode) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'รหัสไม่ถูกต้องหรือหมดอายุ']);
        }

        return view('auth.passwords.reset', compact('email', 'code'));
    }

    /**
     * Reset password with code
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.exists' => 'ไม่พบอีเมลนี้ในระบบ',
            'code.required' => 'กรุณากรอกรหัส',
            'code.size' => 'รหัสต้องมี 6 หลัก',
            'password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->email;
        $code = $request->code;
        $password = $request->password;

        // ตรวจสอบรหัส
        $resetCode = PasswordResetCode::findValid($email, $code);
        
        if (!$resetCode) {
            return back()
                ->withErrors(['code' => 'รหัสไม่ถูกต้องหรือหมดอายุ'])
                ->withInput();
        }

        // อัปเดตรหัสผ่าน
        $user = User::where('email', $email)->first();
        $user->update([
            'password' => Hash::make($password)
        ]);

        // ทำเครื่องหมายว่ารหัสถูกใช้แล้ว
        $resetCode->markAsUsed();

        return redirect()->route('login')
            ->with('status', 'รีเซ็ตรหัสผ่านสำเร็จแล้ว กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่');
    }

    /**
     * Resend reset code
     */
    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'อีเมลไม่ถูกต้อง'
            ], 422);
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        // สร้างรหัสรีเซ็ตใหม่
        $resetCode = PasswordResetCode::createForEmail($email);

        try {
            // ส่งอีเมล
            Mail::to($email)->send(new PasswordResetMail(
                $resetCode->code,
                $user->name,
                $resetCode->expires_at->format('d/m/Y H:i')
            ));

            return response()->json([
                'success' => true,
                'message' => 'ส่งรหัสรีเซ็ตรหัสผ่านใหม่แล้ว'
            ]);
        } catch (\Exception $e) {
            $resetCode->delete();
            
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่งอีเมลได้'
            ], 500);
        }
    }

    /**
     * Verify reset code (for AJAX)
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง'
            ], 422);
        }

        $email = $request->email;
        $code = $request->code;

        $resetCode = PasswordResetCode::findValid($email, $code);
        
        if (!$resetCode) {
            return response()->json([
                'success' => false,
                'message' => 'รหัสไม่ถูกต้องหรือหมดอายุ'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'รหัสถูกต้อง'
        ]);
    }
}
