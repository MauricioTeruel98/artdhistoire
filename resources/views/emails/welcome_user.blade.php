<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Bienvenue sur Art d\'Histoire' : 'Welcome to Art d\'Histoire' }}</title>
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
        <h2>{{ app()->getLocale() == 'fr' ? 'Bienvenue' : 'Welcome' }} {{ $user->name }}!</h2>
    </div>

    <div class="content">
        <p>{{ app()->getLocale() == 'fr' ? 
            'Merci de vous être inscrit sur Art d\'Histoire. Nous sommes ravis de vous compter parmi nos membres.' : 
            'Thank you for registering on Art d\'Histoire. We are delighted to have you as a member.' }}</p>

        <p>{{ app()->getLocale() == 'fr' ? 
            'Vous pouvez maintenant accéder à notre contenu et explorer nos sagas historiques passionnantes.' : 
            'You can now access our content and explore our exciting historical sagas.' }}</p>

        <a href="{{ route('home') }}" class="button" style="color: white;">
            {{ app()->getLocale() == 'fr' ? 'Commencer l\'exploration' : 'Start exploring' }}
        </a>
    </div>
</body>
</html> 