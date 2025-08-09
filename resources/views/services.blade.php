@extends('layouts.app')

@section('title', trans('messages.nav.services') . ' - Jatsmanor')

@section('content')
@include('partials.services-grid')
@endsection
