@extends('layouts.app')

@section('title', $residence['name'] ?? 'Chambre Single')

@section('content')
@include('partials.breadcrumb')

@include('partials.residence-details-main')

@include('partials.similar-residences')
@endsection
