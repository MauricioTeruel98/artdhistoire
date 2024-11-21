<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Réinitialisation du mot de passe' : 'Restablecimiento de contraseña' }}</title>
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
        <h2>{{ app()->getLocale() == 'fr' ? 'Réinitialisation du mot de passe' : 'Restablecimiento de contraseña' }}</h2>
    </div>

    <div class="content">
        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.' 
                : 'Estás recibiendo este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.' }}
        </p>

        <a href="{{ $url }}" class="button">
            {{ app()->getLocale() == 'fr' ? 'Réinitialiser le mot de passe' : 'Restablecer contraseña' }}
        </a>

        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Ce lien expirera dans 60 minutes.' 
                : 'Este enlace expirará en 60 minutos.' }}
        </p>

        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Si vous n\'avez pas demandé de réinitialisation, aucune action n\'est requise.' 
                : 'Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna acción.' }}
        </p>
    </div>
</body>
</html> 