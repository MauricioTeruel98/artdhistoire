<!DOCTYPE html>
<html>
<head>
    <title>Nouvel achat de saga</title>
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
        .logo {
            max-width: 200px;
            margin: 0 auto;
            display: block;
            padding: 20px 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-top: 20px;
        }
        .details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #eee;
        }
        .details ul {
            list-style: none;
            padding: 0;
        }
        .details li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .details li:last-child {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            color: #666;
            font-size: 0.9em;
        }
        .amount {
            font-size: 1.2em;
            font-weight: bold;
            color: #322668;
        }
        .user-info {
            background-color: #f0f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <img src="{{ asset('storage/' . Voyager::setting('site.logo')) }}" alt="Logo" class="logo">
    
    <div class="header">
        <h2>Nouvel achat effectué</h2>
    </div>

    <div class="content">
        <p>Un nouvel achat a été effectué avec les détails suivants:</p>
        
        <div class="user-info">
            <h3>Information de l'utilisateur</h3>
            <p><strong>Nom:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <div class="details">
            <h3>Détails de la commande</h3>
            <ul>
                <li><strong>Saga:</strong> {{ $category->name_fr }}</li>
                <li><strong>Montant:</strong> 
                    <span class="amount">{{ app()->getLocale() == 'fr' ? '€' : '$' }}{{ $amount }}</span>
                </li>
                <li><strong>Date:</strong> {{ now()->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Ceci est un e-mail automatique de notification d'achat.</p>
    </div>
</body>
</html>