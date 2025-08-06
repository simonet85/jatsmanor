@extends('layouts.app')

@section('title', 'Vérification email - Jatsmanor')

@section('styles')
<style>
    body { min-height: 100vh; display: flex; flex-direction: column; }
    main { flex: 1; }
</style>
@endsection

@section('content')
    <!-- Email Verification Form -->
    <main class="flex-1 flex items-center justify-center py-16">
        @include('partials.email-verification-form')
    </main>
@endsection
