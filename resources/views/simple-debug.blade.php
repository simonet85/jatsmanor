<!DOCTYPE html>
<html>
<head>
    <title>Debug Simple</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .info-box { background: #f0f8ff; border: 2px solid #0066cc; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error-box { background: #ffe6e6; border: 2px solid #cc0000; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success-box { background: #e6ffe6; border: 2px solid #00cc00; padding: 15px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Debug Dashboard - État de l'utilisateur</h1>

    @auth
        <div class="success-box">
            <h3>✅ Utilisateur connecté</h3>
            <p><strong>ID:</strong> {{ auth()->user()->id }}</p>
            <p><strong>Nom:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        </div>

        <div class="info-box">
            <h3>🔑 Rôles et permissions</h3>
            @if(method_exists(auth()->user(), 'getRoleNames'))
                <p><strong>Rôles:</strong> {{ auth()->user()->getRoleNames()->implode(', ') ?: 'Aucun rôle assigné' }}</p>
            @else
                <p><strong>Méthode getRoleNames non disponible</strong></p>
            @endif

            @if(method_exists(auth()->user(), 'hasRole'))
                <p><strong>Est administrateur:</strong> {{ auth()->user()->hasRole('Administrator') ? 'OUI' : 'NON' }}</p>
            @else
                <p><strong>Méthode hasRole non disponible</strong></p>
            @endif
        </div>

        <div class="info-box">
            <h3>🎯 Diagnostic du problème</h3>
            @if(method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('Administrator'))
                <p>✅ Vous devriez voir le dashboard administrateur avec les statistiques</p>
                <p>🎯 Le contrôleur devrait charger la vue <code>dashboard.index</code></p>
            @else
                <p>⚠️ Vous n'êtes PAS administrateur</p>
                <p>🎯 Le contrôleur vous redirige vers les réservations client</p>
                <p>💡 C'est probablement pourquoi vous voyez un "autre design"</p>
            @endif
        </div>

        <div class="info-box">
            <h3>🔗 Actions de test</h3>
            <p><a href="/dashboard" style="color: blue;">Aller au dashboard normal</a></p>
            <p><a href="/" style="color: blue;">Retour à l'accueil</a></p>
        </div>
    @else
        <div class="error-box">
            <h3>❌ Utilisateur non connecté</h3>
            <p>Vous devez être connecté pour accéder au dashboard</p>
        </div>
    @endauth
</body>
</html>
