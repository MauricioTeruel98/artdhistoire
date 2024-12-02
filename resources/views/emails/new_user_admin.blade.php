<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Nouvel utilisateur inscrit' : 'New user registered' }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ app()->getLocale() == 'fr' ? 'Nouvel utilisateur inscrit' : 'New user registered' }}</h2>
    </div>

    <div class="content">
        <p><strong>{{ app()->getLocale() == 'fr' ? 'Nom' : 'Name' }}:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>{{ app()->getLocale() == 'fr' ? 'Date d\'inscription' : 'Registration date' }}:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>{{ app()->getLocale() == 'fr' ? 'Statut étudiant' : 'Student status' }}:</strong> 
            {{ $user->is_student ? 
                (app()->getLocale() == 'fr' ? 'Oui' : 'Yes') : 
                (app()->getLocale() == 'fr' ? 'Non' : 'No') }}
        </p>
    </div>
</body>
</html> 