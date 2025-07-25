@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Reset Your Password
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $user->name ?? 'there' }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        We received a request to reset your password. Click the button below to create a new password:
    </p>
    
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.partials.button', ['url' => $resetUrl, 'text' => 'Reset Password'])
    </div>
    
    <p style="margin: 24px 0 16px 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        Or copy and paste this link into your browser:
    </p>
    
    <p style="margin: 0 0 24px 0; word-break: break-all;">
        <a href="{{ $resetUrl }}" style="color: #DC2626; font-size: 14px;">{{ $resetUrl }}</a>
    </p>
    
    @include('emails.partials.info-box', ['content' => '
        <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.6;">
            <strong>Important:</strong> This password reset link will expire in 60 minutes.
        </p>
    '])
    
    <p style="margin: 24px 0 0 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        If you didn't request a password reset, please ignore this email or contact support if you have concerns.
    </p>
@endsection

@section('additional')
    <h3 style="margin: 0 0 12px 0; color: #374151; font-size: 16px; font-weight: 600;">
        Security Tips:
    </h3>
    <ul style="margin: 0; padding: 0 0 0 20px; color: #6B7280; font-size: 14px; line-height: 1.8;">
        <li>Never share your password with anyone</li>
        <li>Use a strong, unique password</li>
        <li>Enable two-factor authentication for extra security</li>
    </ul>
@endsection
