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
        return $this->view('emails.certificate_uploaded')
                    ->subject('Nuevo certificado de estudiante - ' . $this->user->name);
    }
}