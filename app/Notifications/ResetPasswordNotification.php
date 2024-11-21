<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(app()->getLocale() == 'fr' ? 
                'Notification de réinitialisation du mot de passe' : 
                'Password Reset Notification')
            ->line(app()->getLocale() == 'fr' ? 
                'Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.' : 
                'You are receiving this email because we received a password reset request for your account.')
            ->action(app()->getLocale() == 'fr' ? 
                'Réinitialiser le mot de passe' : 
                'Reset Password', 
                $url)
            ->line(app()->getLocale() == 'fr' ? 
                'Ce lien de réinitialisation expirera dans 60 minutes.' : 
                'This reset link will expire in 60 minutes.')
            ->line(app()->getLocale() == 'fr' ? 
                'Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune action n\'est requise.' : 
                'If you did not request a password reset, no further action is required.');
    }
}