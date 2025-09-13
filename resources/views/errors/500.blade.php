@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('error-class', 'error-500')

@section('icon')
<svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('message')
    We're experiencing technical difficulties.<br>
    Our team has been notified and is working on a fix.
@endsection

@section('actions')
    <a href="javascript:window.location.reload()" class="button button-primary">Try again</a>
    <a href="#" onclick="goBack(); return false;" class="button button-secondary">Go back</a>
@endsection
