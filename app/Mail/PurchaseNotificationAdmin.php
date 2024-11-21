<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseNotificationAdmin extends Mailable
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

        return $this->view('emails.purchase_notification_admin')
            ->subject('New purchase of saga: ' . $this->category->name)
            ->withSwiftMessage(function ($message) use ($messageId) {
                $message->getHeaders()
                    ->addTextHeader('Message-ID', '<' . $messageId . '>')
                    ->remove('References')
                    ->remove('In-Reply-To');
            });
    }
}