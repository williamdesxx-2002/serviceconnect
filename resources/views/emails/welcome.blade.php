<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur ServiceConnect</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            margin: 0;
            padding: 20px;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-text {
            font-size: 18px;
            margin-bottom: 30px;
            color: #1f2937;
        }
        .features {
            background: #f8fafc;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        .features h3 {
            margin: 0 0 20px 0;
            color: #4f46e5;
            font-size: 20px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .feature-list li {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list li::before {
            content: "✓";
            color: #10b981;
            font-weight: bold;
            margin-right: 12px;
            font-size: 18px;
        }
        .cta-button {
            display: inline-block;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 30px 0;
            transition: background-color 0.2s;
        }
        .cta-button:hover {
            background-color: #4338ca;
        }
        .footer {
            background: #f3f4f6;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 0 0 10px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .support-info {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .support-info p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .user-info {
            background: #eff6ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #3b82f6;
        }
        .user-info strong {
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-handshake"></i> ServiceConnect
            </div>
            <h1>Bienvenue {{ $user->name }} !</h1>
            <p>Nous sommes ravis de vous compter parmi notre communauté</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="user-info">
                <strong>Votre compte :</strong><br>
                Email : {{ $user->email }}<br>
                Rôle : {{ $user->isProvider() ? 'Prestataire' : 'Client' }}<br>
                @if($user->phone)
                    Téléphone : {{ $user->phone }}
                @endif
            </div>

            <p class="welcome-text">
                Bienvenue sur <strong>ServiceConnect</strong>, votre marketplace de services locaux au Gabon. 
                Notre plateforme vous permet de connecter avec des prestataires qualifiés ou des clients 
                selon vos besoins.
            </p>

            <div class="features">
                <h3>🚀 Ce que vous pouvez faire</h3>
                <ul class="feature-list">
                    @if($user->isProvider())
                        <li>Proposer vos services et trouver de nouveaux clients</li>
                        <li>Gérer vos réservations et votre planning</li>
                        <li>Recevoir des paiements sécurisés</li>
                        <li>Communiquer directement avec les clients</li>
                    @else
                        <li>Trouver des prestataires qualifiés</li>
                        <li>Réserver des services en toute confiance</li>
                        <li>Comparer les prix et les avis</li>
                        <li>Payer en toute sécurité</li>
                    @endif
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="cta-button">
                    Accéder à votre compte
                </a>
            </div>

            <div class="support-info">
                <p>
                    <strong>📞 Besoin d'aide ?</strong><br>
                    Notre équipe support est disponible pour vous aider à toute étape de votre expérience.
                    Contactez-nous à : <strong>support@serviceconnect.ga</strong>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>ServiceConnect</strong> - Marketplace de services locaux au Gabon
            </p>
            <p>
                © {{ date('Y') }} ServiceConnect. Tous droits réservés.
            </p>
            <p>
                Cet email a été envoyé à {{ $user->email }} car vous venez de vous inscrire sur ServiceConnect.
            </p>
        </div>
    </div>
</body>
</html>
