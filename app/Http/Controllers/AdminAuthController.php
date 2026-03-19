<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AdminAuthController extends Controller
{
    private $adminEmail = 'muhammadabbas09dec@gmail.com';
    private $adminPassword = 'abbasamir112233';

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (
            $request->email !== $this->adminEmail ||
            $request->password !== $this->adminPassword
        ) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $otp = rand(100000, 999999);
        Cache::put('admin_otp', $otp, now()->addMinutes(5));

        Mail::raw("Your Aura Admin OTP is: $otp (valid for 5 minutes)", function ($msg) {
            $msg->to('muhammadabbas09dec@gmail.com')
                ->subject('Aura Admin OTP');
        });

        return response()->json(['message' => 'OTP sent to your email']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        $cachedOtp = Cache::get('admin_otp');

        if (!$cachedOtp || $request->otp != $cachedOtp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        Cache::forget('admin_otp');

        return response()->json(['message' => 'OTP verified', 'token' => 'admin_authenticated']);
    }
}