<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use TCG\Voyager\Facades\Voyager;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to(Voyager::setting('site.email_contact'))->send(new ContactFormMail($validatedData));
            return response()->json([
                'success' => true,
                'message' => app()->getLocale() == 'fr' ? 
                    'Votre message a été envoyé avec succès!' : 
                    'Your message has been sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'fr' ? 
                    'Une erreur s\'est produite. Veuillez réessayer.' : 
                    'An error occurred. Please try again.'
            ], 500);
        }
    }
}