<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $certificateUrl;

    public function __construct($user, $certificateUrl)
    {
        $this->user = $user;
        $this->certificateUrl = $certificateUrl;
    }

    public function build()
    {
        $messageId = time() . '.' . uniqid() . '@artdhistoire.com';

        return $this->view('emails.certificate_uploaded')
            ->subject('Certificat étudiant - ' . $this->user->name)
            ->withSwiftMessage(function ($message) use ($messageId) {
                $message->getHeaders()
                    ->addTextHeader('Message-ID', '<' . $messageId . '>')
                    ->remove('References')
                    ->remove('In-Reply-To');
            });
    }
}
