<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PasswordController extends Controller
{
    // This method will handle sending OTP to the user's email
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999); // 6-digit OTP
        $otpExpiration = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes
        session(['otp' => $otp, 'otp_expiration' => $otpExpiration, 'otp_email' => $request->email]);

        Mail::to($request->email)->send(new OtpMail($otp));

        return redirect()->route('password.request')->with('success', 'OTP sent successfully! Please check your email.');
    }

    // This method will verify the OTP entered by the user
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp1' => 'required|numeric',
            'otp2' => 'required|numeric',
            'otp3' => 'required|numeric',
            'otp4' => 'required|numeric',
            'otp5' => 'required|numeric',
            'otp6' => 'required|numeric',
        ]);

        $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6;
        $sessionOtp = session('otp');
        $otpExpiration = session('otp_expiration');
        $email = session('otp_email');

        if (Carbon::now()->gt($otpExpiration)) {
            return redirect()->route('password.request')->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($sessionOtp != $otp) {
            return redirect()->route('password.verifyOtp.page')->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
        return redirect()->route('password.reset.page')->with('otp_verified', 'OTP verified successfully! You can now reset your password.');
    }

    public function resendOtp(Request $request)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please request an OTP first.']);
        }

        $otp = rand(100000, 999999);
        $otpExpiration = Carbon::now()->addMinutes(5);
        session(['otp' => $otp, 'otp_expiration' => $otpExpiration]);

        Mail::to($email)->send(new OtpMail($otp));

        // Redirect without SweetAlert
        return redirect()->route('password.verifyOtp.page')->with('otp_resend_success', 'New OTP sent successfully! Please check your email.');
    }

    // This method will handle resetting the user's password
    public function resetPassword(Request $request)
    {
        // Validate the password
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Find the user by the email stored in the session
        $user = User::where('email', Session::get('otp_email'))->first();

        if ($user) {
            // Update the password
            $user->password = Hash::make($request->password);
            $user->save();

            // Clear the OTP from session after successful password reset
            session()->forget(['otp', 'otp_expiration', 'otp_email']);

            // Flash success message to the session
            return redirect()->route('password.reset.page')->with('success', 'Password reset successfully! You can now log in.');
        }

        return back()->withErrors(['email' => 'User not found']);
    }
}