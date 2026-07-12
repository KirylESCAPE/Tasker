<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Services\TaskService;

class AuthController extends Controller
{
    public function __construct(
        private readonly TaskService $service
    ) {
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if (Auth::attempt($this->service->validateLoginCredentials($request))) {

            $this->service->transferGuestTask();

            return redirect()->intended(route('tasks.index'))->with('success');
        }

        return back()->withErrors(['email' => 'Wrong credentials'])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        Auth::login($this->service->validateCredentials($request));

        $this->service->transferGuestTask();

        return redirect()->route('tasks.index')->with('success', 'Registration successful!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'You have been logged out successfully.');
    }
}
