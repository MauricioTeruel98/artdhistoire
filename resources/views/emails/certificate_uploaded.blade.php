<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Nouveau certificat étudiant' : 'New student certificate' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #322668;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #322668;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ app()->getLocale() == 'fr' ? 'Nouveau certificat étudiant' : 'New student certificate' }}</h2>
    </div>

    <div class="content">
        <p>{{ app()->getLocale() == 'fr' ? 'Un nouveau certificat étudiant a été reçu pour validation.' : 'A new student certificate has been received for validation.' }}</p>

        <div class="info">
            <p><strong>{{ app()->getLocale() == 'fr' ? 'Nom de l\'étudiant:' : 'Student name:' }}</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>{{ app()->getLocale() == 'fr' ? 'Date de téléchargement:' : 'Upload date:' }}</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <p>{{ app()->getLocale() == 'fr' ? 'Vous pouvez voir le certificat en cliquant sur le lien suivant:' : 'You can view the certificate by clicking on the following link:' }}</p>
        
        <a href="{{ $certificateUrl }}" class="button" style="color: white;">
            {{ app()->getLocale() == 'fr' ? 'Voir le certificat' : 'View Certificate' }}
        </a>

        <p>{{ app()->getLocale() == 'fr' ? 'Veuillez examiner le document et mettre à jour le statut de l\'étudiant dans le panneau d\'administration.' : 'Please review the document and update the student status in the admin panel.' }}</p>
    </div>

    <div class="footer">
        <p>{{ app()->getLocale() == 'fr' ? 'Ceci est un e-mail automatique, veuillez ne pas répondre à ce message.' : 'This is an automatic email, please do not reply to this message.' }}</p>
    </div>
</body>
</html>