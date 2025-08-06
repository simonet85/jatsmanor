<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
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
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #e9ecef;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: #6c757d;
        }
        .field {
            margin-bottom: 20px;
        }
        .field label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: white;
            padding: 10px;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        .message-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Nouveau Message de Contact</h1>
        <p>Jatsmanor - Résidences de Prestige</p>
    </div>

    <div class="content">
        <div class="field">
            <label>👤 Nom complet:</label>
            <div class="field-value">{{ $contactData['name'] }}</div>
        </div>

        <div class="field">
            <label>📧 Email:</label>
            <div class="field-value">
                <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
            </div>
        </div>

        <div class="field">
            <label>📞 Téléphone:</label>
            <div class="field-value">
                <a href="tel:{{ $contactData['phone'] }}">{{ $contactData['phone'] }}</a>
            </div>
        </div>

        <div class="field">
            <label>📋 Sujet:</label>
            <div class="field-value">{{ $contactData['subject'] }}</div>
        </div>

        <div class="field">
            <label>💬 Message:</label>
            <div class="message-content">
                {{ $contactData['message'] }}
            </div>
        </div>

        <div class="field">
            <label>🕐 Date de réception:</label>
            <div class="field-value">{{ now()->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Ce message a été envoyé via le formulaire de contact du site Jatsmanor.</p>
        <p>Veuillez répondre directement à l'adresse email du client pour assurer un suivi optimal.</p>
    </div>
</body>
</html>
