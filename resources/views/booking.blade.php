@extends('layouts.app')

@section('title', 'Réservation - Jatsmanor')

@section('content')

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 py-4 text-sm text-gray-500">
        <a href="{{ route('residences') }}" class="hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Retour aux détails
        </a>
    </div>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto px-4 py-6 grid md:grid-cols-3 gap-8">
      <!-- Reservation form -->
      <div class="md:col-span-2 space-y-6">
        <h2 class="text-2xl font-bold mb-2">Finaliser votre réservation</h2>
        
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf
            
            @include('partials.booking-dates')
            @include('partials.booking-customer-info')
            @include('partials.booking-payment')
        </form>
      </div>
      
      <!-- Résumé -->
      <aside>
        @include('partials.booking-summary')
      </aside>
    </main>
@endsection
