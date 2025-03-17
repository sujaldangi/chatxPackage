<?php

namespace Sujal\Chatx\Http\Controllers\Auth;

use Sujal\Chatx\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Sujal\Chatx\Models\User;
use Sujal\Chatx\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use DB;
use Sujal\Chatx\Mail\RegisterMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|unique:users|email:rfc,dns',
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('pictures', 'public');
        } else {
            $picturePath = null;
        }
        // dd($request->device_token);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'picture' => $picturePath,
            'status' => $request->status,
            'device_tokens' => json_encode($request->device_token),
        ]);
        $mailData = [
            'title' => 'Mail from chatX',
            'body' => 'user registered'
        ];

        Mail::to('test@gmail.com')->queue(new RegisterMail($mailData));

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // User Login
    public function login(Request $request)
    {
        \Log::info('in');
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        \Log::info('pass valid' . $request->email);
        $loginAttempt = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        \Log::info('Login attempt result: ' . ($loginAttempt ? 'Success' : 'Failure'));

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            \Log::info('in attemp');
            $user = Auth::user();
            $token = $user->createToken('LaravelPassportToken')->accessToken;
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        } else {
            \Log::info('else');

            return redirect()->route('login')->with('error', 'Invalid email or password.');
        }
    }

    // User Logout
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user && $request->user()->tokens()->count() > 0) {
            $request->user()->tokens()->delete(); // Revoke all tokens
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Successfully logged out');
    }

    public function submitForgetPasswordForm(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);


        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
        ]);


        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function submitResetPasswordForm(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',

        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if (!$passwordReset) {
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $email = PasswordReset::where('token', $request->token)->pluck('email');
        $updatePassword = PasswordReset::where('token', $request->token)->first();


        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $email)
            ->update(['password' => Hash::make($request->password)]);


        PasswordReset::where('token', $request->token)->delete();


        return redirect('/login')->with('message', 'Your password has been changed!');
    }

    public function getUserDetails(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user = $user->toArray();
        unset($user['device_tokens']);

        return response()->json($user);
    }

    public function checkUserStatus(Request $request)
    {
        $userId = $request->input('user_id');
        $isOnline = Cache::has('user-is-online-' . $userId);

        return response()->json(['isOnline' => $isOnline]);
    }

}
