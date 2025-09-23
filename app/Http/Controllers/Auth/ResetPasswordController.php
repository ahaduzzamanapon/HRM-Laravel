<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        $email = session('email');
        $request->session()->flash('email', $email);

        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $email]
        );
    }

    /**
     * Display the OTP entry form.
     *
     * @return \Illuminate\View\View
     */
    public function showOtpForm()
    {
        return view('auth.otp');
    }

    /**
     * Verify the OTP and redirect to the reset password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->otp, $passwordReset->token) || Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // The OTP is correct. Generate a new secure token.
        $token = Str::random(60);

        DB::table('password_resets')->where('email', $request->email)->update([
            'token' => $token,
        ]);

        return redirect()->route('password.reset', ['token' => $token])->with('email', $request->email);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }

        DB::table('users')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset!');
    }
}