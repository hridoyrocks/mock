@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('error-class', 'error-404')

@section('icon')
<svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('message')
    Sorry, we couldn't find the page you're looking for.<br>
    It might have been removed, renamed, or doesn't exist.
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="button button-primary">Back to home</a>
    <a href="#" onclick="goBack(); return false;" class="button button-secondary">Go back</a>
@endsection
