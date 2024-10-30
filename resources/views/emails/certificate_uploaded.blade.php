<!DOCTYPE html>
<html>
<head>
    <title>Nuevo certificado de estudiante subido</title>
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
        .info {
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Nuevo Certificado de Estudiante</h2>
    </div>

    <div class="content">
        <p>Se ha recibido un nuevo certificado de estudiante para su validación.</p>

        <div class="info">
            <p><strong>Nombre del estudiante:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Fecha de subida:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <p>El certificado se encuentra adjunto a este correo para su revisión.</p>
        
        <p>Por favor, revise el documento y actualice el estado del estudiante en el panel de administración.</p>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no responda a este mensaje.</p>
    </div>
</body>
</html>