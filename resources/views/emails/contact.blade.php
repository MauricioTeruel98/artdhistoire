<!DOCTYPE html>
<html>
<head>
    <title>{{ app()->getLocale() == 'fr' ? 'Nouveau message de contact' : 'New contact message' }}</title>
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
        <h2>{{ app()->getLocale() == 'fr' ? 'Nouveau message de contact' : 'New contact message' }}</h2>
    </div>

    <div class="content">
        <p><strong>{{ app()->getLocale() == 'fr' ? 'Nom' : 'Name' }}:</strong> {{ $data['name'] }}</p>
        <p><strong>Email:</strong> {{ $data['email'] }}</p>
        <p><strong>{{ app()->getLocale() == 'fr' ? 'Message' : 'Message' }}:</strong></p>
        <p>{{ $data['message'] }}</p>
    </div>
</body>
</html>