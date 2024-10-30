@extends('layouts.applayout')

@section('title', "Art d'Histoire | Certificado de Estudiante")

@section('header')
<style>
    .certificate-container {
        background-color: #fff;
        padding: 3rem;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }

    .certificate-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .certificate-header h2 {
        color: #322668;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .certificate-header p {
        color: #757575;
        font-size: 1.1rem;
    }

    .user-info {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .user-info h3 {
        color: #322668;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .user-data {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .user-data p {
        margin: 0;
        color: #757575;
    }

    .user-data strong {
        color: #322668;
    }

    .form-upload {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
    }

    .form-group label {
        color: #322668;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .btn-upload {
        background-color: #322668;
        color: white;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        margin-top: 1.5rem;
    }

    .btn-upload:hover {
        background-color: #3F7652;
        color: white;
    }

    .file-instructions {
        font-size: 0.9rem;
        color: #757575;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container certificate-container">
    <div class="certificate-header">
        <h2>{{ app()->getLocale() == 'fr' ? 'Télécharger le Certificat Étudiant' : 'Subir Certificado de Estudiante' }}</h2>
        <p>{{ app()->getLocale() == 'fr' ? 'Pour bénéficier du tarif étudiant, veuillez télécharger votre certificat de scolarité' : 'Para acceder a la tarifa de estudiante, por favor sube tu certificado de estudiante' }}</p>
    </div>

    <div class="user-info">
        <h3>{{ app()->getLocale() == 'fr' ? 'Informations de l\'utilisateur' : 'Información del Usuario' }}</h3>
        <div class="user-data">
            <p><strong>{{ app()->getLocale() == 'fr' ? 'Nom' : 'Nombre' }}:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>{{ app()->getLocale() == 'fr' ? 'Date d\'inscription' : 'Fecha de registro' }}:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
            <p><strong>{{ app()->getLocale() == 'fr' ? 'Statut' : 'Estado' }}:</strong> 
                {{ Auth::user()->is_student ? 
                    (app()->getLocale() == 'fr' ? 'Étudiant (En attente de vérification)' : 'Estudiante (Pendiente de verificación)') : 
                    (app()->getLocale() == 'fr' ? 'Utilisateur standard' : 'Usuario estándar') 
                }}
            </p>
        </div>
    </div>

    <form action="{{ route('certificate.store') }}" method="POST" enctype="multipart/form-data" class="form-upload">
        @csrf
        <input type="hidden" name="category_id" value="{{ $category_id }}">
        <div class="form-group">
            <label for="certificate">{{ app()->getLocale() == 'fr' ? 'Certificat (PDF)' : 'Certificado (PDF)' }}</label>
            <input type="file" class="form-control" id="certificate" name="certificate" accept=".pdf" required>
            <p class="file-instructions">{{ app()->getLocale() == 'fr' ? 'Format accepté: PDF. Taille maximale: 5MB' : 'Formato aceptado: PDF. Tamaño máximo: 5MB' }}</p>
        </div>
        <button type="submit" class="btn btn-upload">
            {{ app()->getLocale() == 'fr' ? 'Télécharger le Certificat' : 'Subir Certificado' }}
        </button>
    </form>
</div>
@endsection