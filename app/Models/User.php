<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Facades\Mail;
use App\Mail\StudentValidated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends \TCG\Voyager\Models\User implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'ip_address_id',
        'is_student',
        'certificate',
        'validated_student',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    protected static function booted()
    {
        static::updated(function ($user) {
            if ($user->wasChanged('validated_student') && $user->validated_student) {
                Mail::to($user->email)->send(new StudentValidated($user));
            }
        });
    }

    public function sendPasswordResetNotification($token)
    {
        try {
            \Log::info('Iniciando envío de notificación de restablecimiento', [
                'user_id' => $this->id,
                'email' => $this->email
            ]);
            
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $this->getEmailForPasswordReset(),
            ], false));
            
            \Mail::send('emails.reset-password', ['url' => $url], function($message) {
                $message->to($this->email)
                       ->subject(app()->getLocale() == 'fr' 
                            ? 'Réinitialisation du mot de passe' 
                            : 'Restablecimiento de contraseña');
            });
            
            \Log::info('Notificación enviada correctamente');
            
        } catch (\Exception $e) {
            \Log::error('Error al enviar notificación de restablecimiento', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
