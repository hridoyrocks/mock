@extends('errors.layout')

@section('title', 'Access Forbidden')
@section('code', '403')
@section('error-class', 'error-403')

@section('icon')
<svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
@endsection

@section('message')
    You don't have permission to access this resource.<br>
    Please check your credentials or contact support.
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="button button-primary">Back to home</a>
    @auth
        <a href="{{ route('dashboard') }}" class="button button-secondary">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="button button-secondary">Sign in</a>
    @endauth
@endsection
