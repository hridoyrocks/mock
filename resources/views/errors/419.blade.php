@extends('errors.layout')

@section('title', 'Session Expired')
@section('code', '419')
@section('error-class', 'error-419')

@section('icon')
<svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('message')
    Your session has expired for security reasons.<br>
    Please refresh the page to continue.
@endsection

@section('actions')
    <a href="javascript:window.location.reload()" class="button button-primary">Refresh page</a>
    <a href="#" onclick="goBack(); return false;" class="button button-secondary">Go back</a>
@endsection
