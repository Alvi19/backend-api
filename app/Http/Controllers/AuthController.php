<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required',
            'password' => 'required|confirmed',
        ]);

        $otp = random_int(1000, 9999);

        $input['otp_code'] = $otp;
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Melakukan Register',
            'data' => $success
        ], 200);
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email_or_phone' => 'required', 'password' => 'required'
        ]);

        $success = false;

        if (filter_var($validate['email_or_phone'], FILTER_VALIDATE_EMAIL)) {

            $validate = $request->validate(['email_or_phone' => 'required|email|exists:users,email', 'password' => 'required']);
            $success = Auth::attempt(['email' => $validate['email_or_phone'], 'password' => $validate['password']]);
        } else {

            $validate = $request->validate(['email_or_phone' => 'required|exists:users,phone', 'password' => 'required']);
            $success = Auth::attempt(['phone' => $validate['email_or_phone'], 'password' => $validate['password']]);
        }

        if (!$success) {

            return response()->json([
                'success' => false,
                'message' => 'Login gagal cek email/nomor telepon dan kata sandi Anda',
            ], 401);
        } else {

            $auth = Auth::user();
            $token = $auth->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => [
                    'token' => $token,
                    'user' => $auth
                ]
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validate = $request->validate([
            'email_or_phone' => 'required',
        ]);

        if (filter_var($validate['email_or_phone'], FILTER_VALIDATE_EMAIL)) {

            $validate = $request->validate(['email_or_phone' => 'required|email|exists:users,email']);
            $user = User::where('email', $validate['email_or_phone'])->first();
        } else {

            $validate = $request->validate(['email_or_phone' => 'required|exists:users,phone']);
            $user = User::where('phone', $validate['email_or_phone'])->first();
        }

        $hash = Str::random(40);

        UserResetPassword::create([
            'user_id' => $user->id,
            'hash' => $hash,
            'expired_at' => Carbon::now()->addHour(1),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cek Email Anda atau Hp Anda'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validate = $request->validate([
            'hash' => 'required|exists:user_reset_passwords,hash',
            'password' => 'required|confirmed',
        ]);

        $resetPasswordData = UserResetPassword::where('hash', $validate['hash'])->first();

        if ($resetPasswordData->expired || now() > $resetPasswordData->expired_at) {
            return response()->json([
                'status' => false,
                'message' =>  'Link Expired'
            ]);
        }

        $userId = $resetPasswordData->user_id;
        $user = User::find($userId);

        $user->password = $validate['password'];
        $user->save();

        $resetPasswordData->expired = true;
        $resetPasswordData->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Mengganti Password'
        ]);
    }
}
