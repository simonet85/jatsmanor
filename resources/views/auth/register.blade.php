@extends('layouts.app')

@section('title', 'Inscription - Jatsmanor')

@section('styles')
<style>
    body { min-height: 100vh; display: flex; flex-direction: column; }
    main { flex: 1; }
</style>
@endsection

@section('content')
    <!-- Inscription Form -->
    <main class="flex-1 flex items-center justify-center py-16">
        @include('partials.registration-form')
    </main>
@endsection
