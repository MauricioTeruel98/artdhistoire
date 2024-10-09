<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        dd($validatedData);

        // Aquí puedes guardar el mensaje en la base de datos si lo deseas

        // Enviar el correo electrónico
        Mail::to('adh@artdhistoire.com')->send(new ContactFormMail($validatedData));

        return response()->json(['success' => true]);
    }
}