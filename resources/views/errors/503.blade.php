@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('error-class', 'error-503')

@section('icon')
<svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
</svg>
@endsection

@section('message')
    We're performing scheduled maintenance.<br>
    The service will be back online shortly.
@endsection

@section('actions')
    <a href="javascript:window.location.reload()" class="button button-primary">Check status</a>
    <a href="#" onclick="alert('Estimated time: 30 minutes')" class="button button-secondary">Notify me</a>
@endsection
