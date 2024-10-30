<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Statut étudiant validé' : 'Student Status Validated' }}</title>
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
        <h2>{{ app()->getLocale() == 'fr' ? 'Félicitations!' : 'Congratulations!' }}</h2>
    </div>

    <div class="content">
        <p>{{ app()->getLocale() == 'fr' ? 'Cher(e)' : 'Dear' }} {{ $user->name }},</p>

        <p>
            {{ app()->getLocale() == 'fr' 
                ? 'Votre statut étudiant a été validé avec succès. Vous pouvez maintenant accéder à nos contenus au tarif étudiant.'
                : 'Your student status has been successfully validated. You can now access our content at student rates.' 
            }}
        </p>

        <a href="{{ route('home') }}" class="button">
            {{ app()->getLocale() == 'fr' ? 'Accéder au site' : 'Access the website' }}
        </a>
    </div>
</body>
</html>