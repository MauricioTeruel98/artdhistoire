<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create($request->only(['nom', 'email']));

        return response()->json(['message' => 'Vous êtes inscrit à la newsletter!']);
    }
}