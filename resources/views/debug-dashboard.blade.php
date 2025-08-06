@extends('layouts.dashboard')

@section('title', 'Debug Dashboard')
@section('subtitle', 'Diagnostic des problèmes')

@section('content')
<div class="p-8">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">🔍 Diagnostic Dashboard</h2>
        
        <div class="space-y-4">
            <div class="p-3 bg-blue-50 rounded">
                <strong>Utilisateur connecté:</strong> {{ auth()->user()->name ?? 'Non connecté' }}
            </div>
            
            <div class="p-3 bg-green-50 rounded">
                <strong>Email:</strong> {{ auth()->user()->email ?? 'N/A' }}
            </div>
            
            <div class="p-3 bg-purple-50 rounded">
                <strong>Rôles:</strong> 
                @if(auth()->check())
                    {{ auth()->user()->getRoleNames()->implode(', ') }}
                @else
                    Aucun
                @endif
            </div>
            
            <div class="p-3 bg-yellow-50 rounded">
                <strong>Permission view-contact-notifications:</strong> 
                @can('view-contact-notifications')
                    ✅ OUI
                @else
                    ❌ NON
                @endcan
            </div>
            
            <div class="p-3 bg-red-50 rounded">
                <strong>Variable $notifications présente:</strong> 
                @if(isset($notifications))
                    ✅ OUI - {{ json_encode($notifications) }}
                @else
                    ❌ NON
                @endif
            </div>
            
            <div class="p-3 bg-gray-50 rounded">
                <strong>Table ContactMessage existe:</strong> 
                @try
                    {{ \App\Models\ContactMessage::count() }} messages
                    ✅ OUI
                @catch(\Exception $e)
                    ❌ ERREUR: {{ $e->getMessage() }}
                @endtry
            </div>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Retour au Dashboard Normal
            </a>
        </div>
    </div>
</div>
@endsection
