<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Shows the registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handles user registration
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'is_student' => ['nullable', 'boolean'],
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_student' => $request->is_student ?? false,
            ]);

            return redirect('/login')->with('success', app()->getLocale() == 'fr'
                ? 'Inscription réussie. Veuillez vous connecter.'
                : 'Successful registration. Please log in.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate error
                return back()
                    ->withInput()
                    ->withErrors(['email' => 'The email is already registered.']);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }
    }

    // Shows the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handles user login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handles user logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Add debug log
            \Log::info('Password reset email sending attempt', [
                'email' => $request->email,
                'status' => $status
            ]);

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => app()->getLocale() == 'fr' 
                    ? 'Nous vous avons envoyé votre lien de réinitialisation par e-mail.'
                    : 'We have emailed your password reset link.'])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            // Error log
            \Log::error('Error sending reset email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['email' => 'Error sending email. Please try again later.']);
        }
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', app()->getLocale() == 'fr'
                ? 'Votre mot de passe a été réinitialisé!'
                : 'Your password has been reset!')
            : back()->withErrors(['email' => __($status)]);
    }
}
