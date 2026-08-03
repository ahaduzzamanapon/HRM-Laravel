<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

class ResetPasswordController extends Controller
{
    /**
     * Display the OTP entry form.
     */
    public function showOtpForm(Request $request)
    {
        $email = session('reset_email') ?? session('email') ?? $request->query('email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please enter your email address to request a password reset.']);
        }
        return view('auth.otp', compact('email'));
    }

    /**
     * Resend OTP to user's email.
     */
    public function resendOtp(Request $request)
    {
        $email = $request->email ?? session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please enter your email address.']);
        }

        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => "We can't find a user with that email address."]);
        }

        $otp = random_int(100000, 999999);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now()
            ]
        );
        session(['reset_email' => $email]);

        try {
            Mail::to($email)->send(new PasswordResetMail($otp));
            return redirect()->route('password.otp')->with('success', 'A new OTP has been sent to your email (' . $email . ').');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Resend OTP failed for ' . $email . ': ' . $e->getMessage());
            return redirect()->route('password.otp')->with('warning', 'Mail notice: Could not send email automatically. For testing, your new OTP is: ' . $otp);
        }
    }

    /**
     * Verify the OTP and redirect to the reset password form.
     */
    public function verifyOtp(Request $request)
    {
        $email = $request->email ?? session('reset_email');

        $request->validate([
            'otp' => 'required',
        ]);

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please request a new password reset.']);
        }

        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['otp' => 'No password reset request found for this email. Please request a new OTP.']);
        }

        $isValidOtp = Hash::check($request->otp, $passwordReset->token);
        $isExpired = $passwordReset->created_at ? Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast() : false;

        if (!$isValidOtp || $isExpired) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please check the code or click Resend OTP.']);
        }

        // Generate secure reset token
        $token = Str::random(60);

        DB::table('password_resets')->where('email', $email)->update([
            'token' => $token,
            'created_at' => now()
        ]);

        session(['reset_email' => $email, 'reset_token' => $token]);

        return redirect()->route('password.reset', ['token' => $token])->with('success', 'OTP verified successfully! Please enter your new password.');
    }

    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, $token = null)
    {
        $email = session('reset_email') ?? $request->query('email');
        if (!$token) {
            $token = session('reset_token');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        $email = $request->email ?? session('reset_email');

        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!$email) {
            return back()->withErrors(['email' => 'Email session expired. Please request a new password reset.']);
        }

        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid or expired reset token. Please request a new password reset.']);
        }

        DB::table('users')
            ->where('email', $email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where('email', $email)->delete();

        session()->forget(['reset_email', 'reset_token']);

        return redirect()->route('login')->with('status', 'Your password has been successfully reset! You can now log in.');
    }
}