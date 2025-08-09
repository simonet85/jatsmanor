@extends('layouts.app')

@section('title', getResidenceName($residence) ?? 'Chambre Single')

@section('content')
@include('partials.breadcrumb')

@include('partials.residence-details-main')

@include('partials.similar-residences')
@endsection
