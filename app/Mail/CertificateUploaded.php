<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $certificatePath;

    public function __construct($user, $certificatePath)
    {
        $this->user = $user;
        $this->certificatePath = $certificatePath;
    }

    public function build()
    {
        return $this->view('emails.certificate_uploaded')
                    ->attach(storage_path('app/public/' . $this->certificatePath));
    }
}