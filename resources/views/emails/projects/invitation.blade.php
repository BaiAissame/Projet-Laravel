<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitation au projet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #2d3748;
            font-size: 24px;
        }
        .button {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invitation au projet</h1>
        
        <p>Bonjour,</p>
        
        <p>Vous avez été invité à rejoindre le projet <strong>{{ $project->name }}</strong>.</p>
        
        @if($userExists)
            <p>Cliquez sur le bouton ci-dessous pour accepter l'invitation et rejoindre le projet :</p>
            <a href="{{ $acceptUrl }}" class="button">Accepter l'invitation</a>
        @else
            <p>Vous devez d'abord créer un compte pour rejoindre ce projet. Cliquez sur le bouton ci-dessous pour créer votre compte :</p>
            <a href="{{ $registerUrl }}" class="button">S'inscrire et rejoindre le projet</a>
        @endif
        
        <div class="footer">
            <p>Merci,<br>
            {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
