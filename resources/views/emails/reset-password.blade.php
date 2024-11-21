<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Réinitialisation du mot de passe' : 'Password Reset' }}</title>
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
            color: white !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ app()->getLocale() == 'fr' ? 'Réinitialisation du mot de passe' : 'Password Reset' }}</h2>
    </div>

    <div class="content">
        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.' 
                : 'You are receiving this email because we received a password reset request for your account.' }}
        </p>

        <a href="{{ $url }}" class="button">
            {{ app()->getLocale() == 'fr' ? 'Réinitialiser le mot de passe' : 'Reset Password' }}
        </a>

        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Ce lien expirera dans 60 minutes.' 
                : 'This link will expire in 60 minutes.' }}
        </p>

        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Si vous n\'avez pas demandé de réinitialisation, aucune action n\'est requise.' 
                : 'If you did not request a password reset, no further action is required.' }}
        </p>
    </div>
</body>
</html> 