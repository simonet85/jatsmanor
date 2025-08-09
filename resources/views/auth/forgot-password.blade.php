@extends('layouts.app')

@section('title', trans('messages.auth.forgot_password_title') . ' - Jatsmanor')

@section('styles')
<style>
    body { min-height: 100vh; display: flex; flex-direction: column; }
    main { flex: 1; }
</style>
@endsection

@section('content')
    <!-- Forgot Password Form -->
    <main class="flex-1 flex items-center justify-center py-16">
        @include('partials.forgot-password-form')
    </main>
@endsection
