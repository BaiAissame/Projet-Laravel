<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau membre ajouté au projet</title>
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
        <h1>Nouveau membre ajouté au projet</h1>
        
        <p>Bonjour {{ $recipient->firstname }},</p>
        
        <p>Un nouveau membre a rejoint le projet <strong>{{ $project->name }}</strong>.</p>
        
        <p><strong>Nouveau membre :</strong> {{ $newMember->firstname }} {{ $newMember->lastname }} ({{ $newMember->email }})</p>
        
        <a href="{{ route('kanban.index', $project) }}" class="button">Voir le projet</a>
        
        <div class="footer">
            <p>Merci,<br>
            {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
