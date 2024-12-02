<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $messageId = time() . '.' . uniqid() . '@artdhistoire.com';

        return $this->view('emails.welcome_user')
            ->subject(app()->getLocale() == 'fr' ? 
                'Bienvenue sur Art d\'Histoire' : 
                'Welcome to Art d\'Histoire')
            ->withSwiftMessage(function ($message) use ($messageId) {
                $message->getHeaders()
                    ->addTextHeader('Message-ID', '<' . $messageId . '>')
                    ->remove('References')
                    ->remove('In-Reply-To');
            });
    }
} 