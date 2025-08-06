<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de réservation - {{ $booking->booking_reference }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .booking-card {
            background-color: #f8f9fa;
            border-left: 4px solid #007BFF;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        
        .booking-reference {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        
        @media (max-width: 600px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .detail-item {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        
        .detail-label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: #007BFF;
            font-weight: 500;
        }
        
        .residence-info {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .residence-name {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 10px;
        }
        
        .amount-total {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #155724;
        }
        
        .custom-message {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffd93d;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .practical-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer p {
            margin: 5px 0;
            opacity: 0.8;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>JATSMANOR</h1>
            <p>Confirmation de votre réservation</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h2>Bonjour {{ $booking->first_name }} {{ $booking->last_name }},</h2>
            
            <p>Nous avons le plaisir de vous confirmer votre réservation chez Jatsmanor. Tous les détails de votre séjour sont confirmés et nous nous réjouissons de vous accueillir !</p>
            
            <!-- Booking Reference -->
            <div class="booking-reference">
                Référence : {{ $booking->booking_reference }}
            </div>
            
            <!-- Residence Information -->
            <div class="residence-info">
                <div class="residence-name">{{ $booking->residence->name }}</div>
                <p><strong>📍 Localisation :</strong> {{ $booking->residence->location }}</p>
                <p><strong>🏠 Surface :</strong> {{ $booking->residence->surface }}m² • <strong>👥 Capacité :</strong> {{ $booking->residence->max_guests }} personnes maximum</p>
            </div>
            
            <!-- Booking Details Grid -->
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">🗓️ Arrivée</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">🗓️ Départ</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">🌙 Nombre de nuits</span>
                    <span class="detail-value">{{ $booking->total_nights }} {{ $booking->total_nights > 1 ? 'nuits' : 'nuit' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">👥 Nombre d'invités</span>
                    <span class="detail-value">{{ $booking->guests }} {{ $booking->guests > 1 ? 'personnes' : 'personne' }}</span>
                </div>
            </div>
            
            <!-- Total Amount -->
            <div class="amount-total">
                <div>Montant total</div>
                <div class="amount-value">{{ number_format($booking->total_amount, 0, ',', '.') }} FCFA</div>
            </div>
            
            @if($customMessage)
            <!-- Custom Message -->
            <div class="custom-message">
                <strong>💬 Message personnalisé :</strong><br>
                {{ $customMessage }}
            </div>
            @endif
            
            <!-- Practical Information -->
            <div class="practical-info">
                <h3>ℹ️ Informations pratiques</h3>
                <div class="info-grid">
                    <div>
                        <strong>⏰ Check-in :</strong><br>
                        À partir de 14h00
                    </div>
                    <div>
                        <strong>⏰ Check-out :</strong><br>
                        Avant 11h00
                    </div>
                    <div>
                        <strong>📞 Contact :</strong><br>
                        +225 07 07 07 07
                    </div>
                    <div>
                        <strong>✉️ Email :</strong><br>
                        contact@jatsmanor.ci
                    </div>
                </div>
            </div>
            
            @if($booking->special_requests)
            <!-- Special Requests -->
            <div class="booking-card">
                <strong>🎯 Vos demandes spéciales :</strong><br>
                {{ $booking->special_requests }}
            </div>
            @endif
            
            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ route('booking.show', $booking) }}" class="btn">
                    📋 Voir ma réservation complète
                </a>
            </div>
            
            <p>Si vous avez des questions ou des demandes particulières, n'hésitez pas à nous contacter. Notre équipe est à votre disposition pour faire de votre séjour une expérience inoubliable.</p>
            
            <p><strong>À très bientôt chez Jatsmanor !</strong></p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>L'équipe Jatsmanor</strong></p>
            <p>Votre partenaire pour un séjour d'exception en Côte d'Ivoire</p>
            <div class="social-links">
                <a href="mailto:contact@jatsmanor.ci">✉️ Email</a>
                <a href="tel:+22507070707">📞 Téléphone</a>
            </div>
            <p style="font-size: 12px; margin-top: 20px;">
                Cet email a été envoyé automatiquement le {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</body>
</html>
