<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function requestEmailVerification(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);
        $email = strtolower(trim($validated['email']));

        if (! Booking::where('client_email', '=', $email, 'and')->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No booking was found for this email address.',
            ], 404);
        }

        $code = (string) random_int(100000, 999999);
        Cache::put('booking_lookup_otp:' . $email, $code, now()->addMinutes(10));

        Mail::raw("Your Amiga Gracia booking verification code is {$code}. It expires in 10 minutes.", function ($message) use ($email): void {
            $message->to($email)->subject('Amiga Gracia booking verification code');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'A verification code was sent to your email.',
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);
        $email = strtolower(trim($validated['email']));
        $cacheKey = 'booking_lookup_otp:' . $email;

        if (! hash_equals((string) Cache::get($cacheKey, ''), $validated['code'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'The verification code is invalid or expired.',
            ], 422);
        }

        Cache::forget($cacheKey);
        User::where('email', '=', $email, 'and')->update(['email_verified_at' => now()]);
        $lookupToken = Str::random(80);
        Cache::put('booking_lookup_token:' . hash('sha256', $lookupToken), $email, now()->addDays(30));

        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully.',
            'email' => $email,
            'lookup_token' => $lookupToken,
        ]);
    }

    private function issueLookupToken(string $email): string
    {
        $lookupToken = Str::random(80);
        Cache::put('booking_lookup_token:' . hash('sha256', $lookupToken), strtolower($email), now()->addDays(30));

        return $lookupToken;
    }

    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            $this->logUserLogin(
                null,
                'web_login',
                $request,
                false,
                'Failed web login attempt.'
            );

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        $this->logUserLogin(
            Auth::user(),
            'web_login',
            $request,
            true,
            'Successful web login.'
        );

        return redirect()->intended(route('dashboard'));
    }

    protected function logUserLogin(?User $user, string $type, Request $request, bool $success, string $description = null): void
    {
        \App\Models\UserLoginHistory::create([
            'user_id' => $user?->id,
            'email' => $request->input('email'),
            'login_type' => $type,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'success' => $success,
            'description' => $description,
            'metadata' => [
                'remember' => $request->boolean('remember'),
            ],
        ]);
    }

    public function showRegister(): View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validated);

        Auth::login($user);

        $request->session()->regenerate();

        $this->logUserLogin(
            $user,
            'web_register',
            $request,
            true,
            'Successful web registration.'
        );

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('book');
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            $this->logUserLogin(
                null,
                'api_login',
                $request,
                false,
                'Failed API login attempt.'
            );

            return response()->json([
                'message' => 'These credentials do not match our records.'
            ], 422);
        }

        $user = Auth::user();
        $user->api_token = Str::random(80);
        $user->save();

        $this->logUserLogin(
            $user,
            'api_login',
            $request,
            true,
            'Successful API login.'
        );

        return response()->json([
            'status' => 'success',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $user->api_token,
            'lookup_token' => $this->issueLookupToken($user->email),
        ]);
    }

    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $validated['api_token'] = Str::random(80);

        $user = User::create($validated);

        $this->logUserLogin(
            $user,
            'api_register',
            $request,
            true,
            'Successful API registration.'
        );

        return response()->json([
            'status' => 'success',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $user->api_token,
            'lookup_token' => $this->issueLookupToken($user->email),
        ]);
    }

    /**
     * Step 1 of OTP-gated registration: validate inputs, cache pending data, send OTP email.
     */
    public function requestRegisterOtp(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $email = strtolower(trim($validated['email']));
        $otp   = (string) random_int(100000, 999999);

        // Cache the pending registration data for 10 minutes
        Cache::put('pending_register:' . $email, [
            'name'     => $validated['name'],
            'email'    => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'otp'      => $otp,
        ], now()->addMinutes(10));

        Mail::raw(
            "Hello {$validated['name']},\n\nYour Amiga Gracia registration verification code is: {$otp}\n\nThis code expires in 10 minutes. Do not share it with anyone.",
            function ($message) use ($email, $validated): void {
                $message->to($email)->subject('Amiga Gracia – Email Verification Code');
            }
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'A 6-digit verification code has been sent to your email.',
        ]);
    }

    /**
     * Step 2 of OTP-gated registration: verify OTP and create the user account.
     */
    public function verifyRegisterOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $email   = strtolower(trim($validated['email']));
        $pending = Cache::get('pending_register:' . $email);

        if (! $pending) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Registration session expired. Please start over.',
            ], 422);
        }

        if (! hash_equals((string) $pending['otp'], $validated['otp'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or expired verification code.',
            ], 422);
        }

        // Double-check uniqueness (race condition guard)
        if (User::where('email', $email)->exists()) {
            Cache::forget('pending_register:' . $email);
            return response()->json([
                'status'  => 'error',
                'message' => 'An account with this email already exists.',
            ], 422);
        }

        Cache::forget('pending_register:' . $email);

        $user = User::create([
            'name'              => $pending['name'],
            'email'             => $pending['email'],
            'password'          => $pending['password'],
            'api_token'         => Str::random(80),
            'email_verified_at' => now(),
        ]);

        $this->logUserLogin(
            $user,
            'api_register',
            $request,
            true,
            'Successful OTP-verified API registration.'
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Account created successfully!',
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'token'        => $user->api_token,
            'lookup_token' => $this->issueLookupToken($user->email),
        ]);
    }
}
