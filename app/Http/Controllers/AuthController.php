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
use App\Mail\NewUserNotificationAdmin;
use App\Mail\WelcomeUser;
use Illuminate\Support\Facades\Mail;
use TCG\Voyager\Facades\Voyager;

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
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255', 
                    'unique:users',
                    function ($attribute, $value, $fail) {
                        // Verificar si el dominio del correo existe
                        $domain = substr(strrchr($value, "@"), 1);
                        if (!checkdnsrr($domain, "MX")) {
                            $fail(app()->getLocale() == 'fr' 
                                ? 'L\'adresse email fournie n\'est pas valide ou n\'existe pas.' 
                                : 'The provided email is not valid or does not exist.');
                        }
                    },
                ],
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[A-Z]/',    
                    'regex:/[0-9]/',    
                ],
                'is_student' => ['nullable', 'boolean'],
            ], [
                'email.unique' => app()->getLocale() == 'fr'
                    ? 'Cette adresse email est déjà utilisée.'
                    : 'Este correo electrónico ya está registrado.',
                'password.min' => app()->getLocale() == 'fr' 
                    ? 'Le mot de passe doit contenir au moins 8 caractères.'
                    : 'La contraseña debe tener al menos 8 caracteres.',
                'password.regex' => app()->getLocale() == 'fr'
                    ? 'Le mot de passe doit contenir au moins une majuscule et un chiffre.'
                    : 'La contraseña debe contener al menos una mayúscula y un número.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_student' => $request->is_student ?? false,
            ]);

            // Enviar emails
            Mail::to(Voyager::setting('site.email_contact'))->send(new NewUserNotificationAdmin($user));
            Mail::to($user->email)->send(new WelcomeUser($user));

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
            'email' => ['required', 'email', 'exists:users'],
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => app()->getLocale() == 'fr'
                    ? 'Nous vous avons envoyé votre lien de réinitialisation par e-mail.'
                    : 'Te hemos enviado el enlace de restablecimiento por correo electrónico.'])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            \Log::error('Error en envío de correo de restablecimiento', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['email' => app()->getLocale() == 'fr'
                ? 'Erreur lors de l\'envoi de l\'e-mail. Veuillez réessayer plus tard.'
                : 'Error al enviar el correo. Por favor intenta más tarde.']);
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
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',    // Al menos una mayúscula
                'regex:/[0-9]/',    // Al menos un número
            ],
        ], [
            'password.min' => app()->getLocale() == 'fr' 
                ? 'Le mot de passe doit contenir au moins 8 caractères.'
                : 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => app()->getLocale() == 'fr'
                ? 'Le mot de passe doit contenir au moins une majuscule et un chiffre.'
                : 'La contraseña debe contener al menos una mayúscula y un número.',
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
                : '¡Tu contraseña ha sido restablecida!')
            : back()->withErrors(['email' => __($status)]);
    }
}
