<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
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
        $user->api_token = \Illuminate\Support\Str::random(80);
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
        $validated['api_token'] = \Illuminate\Support\Str::random(80);

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
        ]);
    }
}
