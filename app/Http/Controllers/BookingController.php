<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Residence;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show booking form for a residence
     */
    public function create(Residence $residence)
    {
        return view('booking.create', compact('residence'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request, Residence $residence)
    {
        $validated = $request->validate([
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:' . ($residence->max_guests ?? 10)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'newsletter_subscription' => ['boolean'],
            'terms_accepted' => ['accepted'],
        ], [
            'check_in_date.required' => 'La date d\'arrivée est obligatoire.',
            'check_in_date.after_or_equal' => 'La date d\'arrivée ne peut pas être antérieure à aujourd\'hui.',
            'check_out_date.required' => 'La date de départ est obligatoire.',
            'check_out_date.after' => 'La date de départ doit être postérieure à la date d\'arrivée.',
            'guests_count.max' => 'Le nombre d\'invités ne peut pas dépasser ' . ($residence->max_guests ?? 10) . ' personnes.',
            'terms_accepted.accepted' => 'Vous devez accepter les conditions générales.',
        ]);

        // Vérifier la disponibilité
        $checkAvailability = $this->checkAvailability(
            $residence,
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        if (!$checkAvailability['available']) {
            return back()->withErrors(['check_in_date' => $checkAvailability['message']])
                        ->withInput();
        }

        // Calculer le montant total
        $checkinDate = Carbon::parse($validated['check_in_date']);
        $checkoutDate = Carbon::parse($validated['check_out_date']);
        $nights = $checkinDate->diffInDays($checkoutDate);
        $pricePerNight = $residence->price_per_night;
        $subtotal = $nights * $pricePerNight;
        $totalAmount = $subtotal;

        // Créer la réservation
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'residence_id' => $residence->id,
            'check_in' => $validated['check_in_date'],
            'check_out' => $validated['check_out_date'],
            'guests' => $validated['guests_count'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'],
            'special_requests' => $validated['special_requests'],
            'total_nights' => $nights,
            'price_per_night' => $pricePerNight,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'booking_reference' => $this->generateBookingReference(),
        ]);

        // Souscrire à la newsletter si demandé
        if ($request->boolean('newsletter_subscription')) {
            // Logique pour ajouter à la newsletter
            // Vous pouvez créer un modèle Newsletter ou utiliser un service tiers
        }

        return redirect()->route('booking.confirmation', $booking)
                        ->with('success', 'Votre réservation a été créée avec succès !');
    }

    /**
     * Show booking confirmation
     */
    public function confirmation(Booking $booking)
    {
        // Vérifier que l'utilisateur peut voir cette réservation
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Charger les relations nécessaires
        $booking->load(['residence.images', 'user']);

        return view('booking.confirmation', compact('booking'));
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['residence.images', 'payments']);
        
        return view('booking.show', compact('booking'));
    }

    /**
     * Show user's bookings
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
                        ->with(['residence.images'])
                        ->latest()
                        ->paginate(10);

        return view('booking.index', compact('bookings'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        // Vérifier que l'utilisateur peut annuler cette réservation
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que la réservation peut être annulée (48h avant)
        $checkinDate = Carbon::parse($booking->check_in);
        $now = Carbon::now();

        if ($now->diffInHours($checkinDate) < 48) {
            return back()->withErrors(['error' => 'Vous ne pouvez plus annuler cette réservation (moins de 48h avant l\'arrivée).']);
        }

        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return back()->withErrors(['error' => 'Cette réservation ne peut pas être annulée.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Votre réservation a été annulée avec succès.');
    }

    /**
     * Check availability for given dates
     */
    private function checkAvailability(Residence $residence, $checkinDate, $checkoutDate)
    {
        // Vérifier les réservations existantes
        $conflictingBookings = Booking::where('residence_id', $residence->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkinDate, $checkoutDate) {
                $query->whereBetween('check_in', [$checkinDate, $checkoutDate])
                      ->orWhereBetween('check_out', [$checkinDate, $checkoutDate])
                      ->orWhere(function ($q) use ($checkinDate, $checkoutDate) {
                          $q->where('check_in', '<=', $checkinDate)
                            ->where('check_out', '>=', $checkoutDate);
                      });
            })
            ->exists();

        if ($conflictingBookings) {
            return [
                'available' => false,
                'message' => 'Cette résidence n\'est pas disponible pour les dates sélectionnées.'
            ];
        }

        return ['available' => true];
    }

    /**
     * Generate a unique booking reference
     */
    private function generateBookingReference()
    {
        do {
            $reference = 'JM' . strtoupper(substr(uniqid(), -8));
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Send confirmation email
     */
    public function sendConfirmation(Request $request, Booking $booking)
    {
        // Vérifier que l'utilisateur peut accéder à cette réservation
        if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:1000'
        ]);

        try {
            // Créer un utilisateur temporaire avec l'email fourni pour l'envoi
            $recipient = new \App\Models\User([
                'name' => $booking->first_name . ' ' . $booking->last_name,
                'email' => $validated['email']
            ]);

            // Envoyer la notification
            $recipient->notify(new \App\Notifications\BookingConfirmationNotification(
                $booking, 
                $validated['message'] ?? null
            ));

            // Enregistrer l'activité dans les logs
            \Illuminate\Support\Facades\Log::info('Email de confirmation envoyé', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'email' => $validated['email'],
                'sent_by' => Auth::user()->email,
                'custom_message' => $validated['message'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email de confirmation envoyé avec succès à ' . $validated['email']
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur envoi email confirmation', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate and download invoice PDF
     */
    public function generateInvoice(Booking $booking)
    {
        // Vérifier que l'utilisateur peut accéder à cette réservation
        if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            // Charger les relations nécessaires
            $booking->load('residence', 'residence.images');
            
            // Générer le HTML de la facture
            $html = $this->generateInvoiceHTML($booking);
            
            // Générer le PDF avec DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'chroot' => public_path(),
                ]);

            // Enregistrer l'activité dans les logs
            \Illuminate\Support\Facades\Log::info('Facture générée', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'generated_by' => Auth::user()->email,
                'generated_at' => now()
            ]);

            // Retourner le PDF pour téléchargement
            return $pdf->download('facture-' . $booking->booking_reference . '.pdf');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur génération facture', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération de la facture : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate invoice HTML
     */
    private function generateInvoiceHTML(Booking $booking)
    {
        $invoiceNumber = 'FAC-' . $booking->booking_reference;
        $currentDate = now()->format('d/m/Y');
        $checkIn = \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y');
        $checkOut = \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y');
        
        return '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="utf-8">
            <title>Facture - ' . $booking->booking_reference . '</title>
            <style>
                @page {
                    margin: 20mm;
                    size: A4;
                }
                
                body { 
                    font-family: "DejaVu Sans", sans-serif; 
                    margin: 0; 
                    padding: 0;
                    font-size: 12px;
                    line-height: 1.4;
                    color: #333;
                }
                
                .header { 
                    text-align: center; 
                    margin-bottom: 40px; 
                    border-bottom: 3px solid #007BFF;
                    padding-bottom: 20px;
                }
                
                .company { 
                    font-size: 28px; 
                    font-weight: bold; 
                    color: #007BFF; 
                    margin-bottom: 10px;
                }
                
                .company-info {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 5px;
                }
                
                .invoice-title {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                    text-align: center;
                    margin: 30px 0;
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-left: 5px solid #007BFF;
                }
                
                .info-section {
                    margin: 25px 0;
                    overflow: hidden;
                }
                
                .info-left, .info-right {
                    width: 48%;
                    float: left;
                }
                
                .info-right {
                    float: right;
                }
                
                .info-box {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    margin-bottom: 15px;
                }
                
                .info-title {
                    font-weight: bold;
                    font-size: 14px;
                    color: #007BFF;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #dee2e6;
                    padding-bottom: 5px;
                }
                
                .info-line {
                    margin: 8px 0;
                    display: block;
                }
                
                .label {
                    font-weight: bold;
                    color: #495057;
                    display: inline-block;
                    width: 140px;
                }
                
                .table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin: 30px 0;
                    background-color: white;
                }
                
                .table th {
                    background-color: #007BFF;
                    color: white;
                    padding: 12px 8px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #0056b3;
                }
                
                .table td {
                    border: 1px solid #dee2e6;
                    padding: 12px 8px;
                    vertical-align: top;
                }
                
                .table tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                
                .total-row {
                    font-weight: bold;
                    background-color: #e3f2fd !important;
                    font-size: 14px;
                }
                
                .amount {
                    text-align: right;
                    font-weight: bold;
                    color: #007BFF;
                }
                
                .total-amount {
                    font-size: 16px;
                    color: #007BFF;
                }
                
                .footer { 
                    margin-top: 50px; 
                    text-align: center; 
                    font-size: 11px; 
                    color: #666;
                    border-top: 1px solid #dee2e6;
                    padding-top: 20px;
                }
                
                .status-badge {
                    display: inline-block;
                    padding: 5px 10px;
                    border-radius: 15px;
                    font-size: 11px;
                    font-weight: bold;
                    text-transform: uppercase;
                }
                
                .status-confirmed {
                    background-color: #d4edda;
                    color: #155724;
                }
                
                .status-pending {
                    background-color: #fff3cd;
                    color: #856404;
                }
                
                .clearfix::after {
                    content: "";
                    display: table;
                    clear: both;
                }
                
                .residence-image {
                    max-width: 100px;
                    height: auto;
                    border-radius: 5px;
                    float: left;
                    margin-right: 15px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company">JATSMANOR</div>
                <div class="company-info">Résidences de luxe en Côte d\'Ivoire</div>
                <div class="company-info">Email: contact@jatsmanor.ci | Téléphone: +225 07 07 07 07</div>
                <div class="company-info">Abidjan, Côte d\'Ivoire</div>
            </div>

            <div class="invoice-title">FACTURE N° ' . $invoiceNumber . '</div>

            <div class="info-section clearfix">
                <div class="info-left">
                    <div class="info-box">
                        <div class="info-title">INFORMATIONS CLIENT</div>
                        <div class="info-line">
                            <span class="label">Nom complet :</span>
                            ' . $booking->first_name . ' ' . $booking->last_name . '
                        </div>
                        <div class="info-line">
                            <span class="label">Email :</span>
                            ' . $booking->email . '
                        </div>
                        <div class="info-line">
                            <span class="label">Téléphone :</span>
                            ' . $booking->phone . '
                        </div>
                        ' . ($booking->company ? '
                        <div class="info-line">
                            <span class="label">Entreprise :</span>
                            ' . $booking->company . '
                        </div>' : '') . '
                    </div>
                </div>
                
                <div class="info-right">
                    <div class="info-box">
                        <div class="info-title">DÉTAILS FACTURE</div>
                        <div class="info-line">
                            <span class="label">Date facture :</span>
                            ' . $currentDate . '
                        </div>
                        <div class="info-line">
                            <span class="label">Référence :</span>
                            ' . $booking->booking_reference . '
                        </div>
                        <div class="info-line">
                            <span class="label">Statut :</span>
                            <span class="status-badge status-' . $booking->status . '">' . ucfirst($booking->status) . '</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="info-box">
                    <div class="info-title">RÉSIDENCE RÉSERVÉE</div>
                    <div style="overflow: hidden;">
                        <div style="margin-bottom: 10px;">
                            <strong style="font-size: 16px; color: #007BFF;">' . $booking->residence->name . '</strong>
                        </div>
                        <div class="info-line">
                            <span class="label">Localisation :</span>
                            ' . $booking->residence->location . '
                        </div>
                        <div class="info-line">
                            <span class="label">Surface :</span>
                            ' . $booking->residence->surface . ' m²
                        </div>
                        <div class="info-line">
                            <span class="label">Capacité max :</span>
                            ' . $booking->residence->max_guests . ' personnes
                        </div>
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Description</th>
                        <th style="width: 20%;">Période</th>
                        <th style="width: 15%;">Nuits</th>
                        <th style="width: 15%;">Prix/nuit</th>
                        <th style="width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>' . $booking->residence->name . '</strong><br>
                            <small style="color: #666;">' . $booking->residence->location . '</small><br>
                            <small style="color: #666;">Invités: ' . $booking->guests . '</small>
                        </td>
                        <td>
                            <strong>Du :</strong> ' . $checkIn . '<br>
                            <strong>Au :</strong> ' . $checkOut . '
                        </td>
                        <td style="text-align: center;">
                            <strong>' . $booking->total_nights . '</strong>
                        </td>
                        <td class="amount">
                            ' . number_format($booking->price_per_night, 0, ',', '.') . ' FCFA
                        </td>
                        <td class="amount">
                            ' . number_format($booking->total_amount, 0, ',', '.') . ' FCFA
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" style="text-align: right; padding-right: 20px;">
                            <strong>MONTANT TOTAL À PAYER</strong>
                        </td>
                        <td class="amount total-amount">
                            <strong>' . number_format($booking->total_amount, 0, ',', '.') . ' FCFA</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            ' . ($booking->special_requests ? '
            <div class="info-section">
                <div class="info-box">
                    <div class="info-title">DEMANDES SPÉCIALES</div>
                    <p style="margin: 0; font-style: italic;">' . $booking->special_requests . '</p>
                </div>
            </div>' : '') . '

            <div class="info-section">
                <div class="info-box">
                    <div class="info-title">INFORMATIONS PRATIQUES</div>
                    <div style="overflow: hidden;">
                        <div style="width: 48%; float: left;">
                            <div class="info-line">
                                <span class="label">Check-in :</span>
                                À partir de 14h00
                            </div>
                            <div class="info-line">
                                <span class="label">Check-out :</span>
                                Avant 11h00
                            </div>
                        </div>
                        <div style="width: 48%; float: right;">
                            <div class="info-line">
                                <span class="label">Contact :</span>
                                +225 07 07 07 07
                            </div>
                            <div class="info-line">
                                <span class="label">Email :</span>
                                contact@jatsmanor.ci
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div style="margin-bottom: 10px;">
                    <strong>Merci de votre confiance et de votre fidélité !</strong>
                </div>
                <div>
                    Cette facture a été générée automatiquement le ' . now()->format('d/m/Y à H:i') . '
                </div>
                <div style="margin-top: 10px; font-size: 10px;">
                    Jatsmanor - Votre partenaire pour un séjour d\'exception en Côte d\'Ivoire
                </div>
            </div>
        </body>
        </html>';
    }
}
