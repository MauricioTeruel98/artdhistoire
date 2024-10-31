<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateUploaded;

class CertificateController extends Controller
{
    public function showUploadForm($category_id)
    {
        return view('auth.certificate.upload', compact('category_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'certificate' => 'required|mimes:pdf|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = auth()->user();
        $certificatePath = $request->file('certificate')->store('certificates', 'public');

        $user->certificate = $certificatePath;
        $user->is_student = true;
        $user->validated_student = false;
        $user->save();

        // Generar URL pública del certificado
        $certificateUrl = url('storage/' . $certificatePath);

        // Enviar correo al administrador con el enlace
        Mail::to('admin@example.com')->send(new CertificateUploaded($user, $certificateUrl));

        return redirect()->route('certificate.pending');
    }
}
