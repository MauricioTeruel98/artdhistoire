<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Confirmation d\'achat' : 'Purchase confirmation' }}</title>
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
    </style>
</head>
<body>
    <img src="{{ asset('storage/' . Voyager::setting('site.logo')) }}" alt="Logo" class="logo">
    
    <div class="header">
        <h2>{{ app()->getLocale() == 'fr' ? 'Confirmation de votre achat' : 'Purchase confirmation' }}</h2>
    </div>

    <div class="content">
        <p>{{ app()->getLocale() == 'fr' ? 'Merci pour votre achat!' : 'Thank you for your purchase!' }}</p>
        
        <div class="details">
            <h3>{{ app()->getLocale() == 'fr' ? 'Détails de la commande:' : 'Order details:' }}</h3>
            <ul>
                <li><strong>{{ app()->getLocale() == 'fr' ? 'Saga' : 'Saga' }}:</strong> 
                    {{ app()->getLocale() == 'fr' ? $category->name_fr : $category->name }}
                </li>
                <li><strong>{{ app()->getLocale() == 'fr' ? 'Montant' : 'Amount' }}:</strong> 
                    <span class="amount">{{ app()->getLocale() == 'fr' ? '€' : '$' }}{{ $amount }}</span>
                </li>
                <li><strong>{{ app()->getLocale() == 'fr' ? 'Date' : 'Date' }}:</strong> 
                    {{ now()->format('d/m/Y H:i') }}
                </li>
            </ul>
        </div>

        <p>{{ app()->getLocale() == 'fr' ? 'Vous pouvez maintenant accéder à tout le contenu de la saga.' : 'You can now access all the saga content.' }}</p>
    </div>

    <div class="footer">
        <p>{{ app()->getLocale() == 'fr' ? 'Ceci est un e-mail automatique, veuillez ne pas répondre à ce message.' : 'This is an automatic email, please do not reply to this message.' }}</p>
    </div>
</body>
</html>