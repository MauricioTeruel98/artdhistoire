<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $category;
    public $amount;

    public function __construct($user, $category, $amount)
    {
        $this->user = $user;
        $this->category = $category;
        $this->amount = $amount;
    }

    public function build()
    {
        $messageId = time() . '.' . uniqid() . '@artdhistoire.com';

        return $this->view('emails.purchase_confirmation')
            ->subject(app()->getLocale() == 'fr' ? 
                'Confirmation de votre achat - ' . $this->category->name :
                'Purchase confirmation - ' . $this->category->name)
            ->withSwiftMessage(function ($message) use ($messageId) {
                $message->getHeaders()
                    ->addTextHeader('Message-ID', '<' . $messageId . '>')
                    ->remove('References')
                    ->remove('In-Reply-To');
            });
    }
}