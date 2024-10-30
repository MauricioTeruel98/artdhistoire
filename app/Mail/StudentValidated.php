<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentValidated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.student_validated')
                    ->subject(app()->getLocale() == 'fr' ? 
                        'Votre statut étudiant a été validé' : 
                        'Your student status has been validated');
    }
}