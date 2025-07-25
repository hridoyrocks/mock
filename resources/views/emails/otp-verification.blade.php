@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Verify Your Email Address
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $user->name ?? 'there' }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Thank you for registering with IELTS Mock Platform. To complete your registration, please enter the verification code below:
    </p>
    
    <div style="text-align: center; margin: 32px 0;">
        <div style="display: inline-block; background-color: #FEF2F2; border: 2px dashed #DC2626; border-radius: 12px; padding: 24px 48px;">
            <h1 style="margin: 0; color: #DC2626; font-size: 36px; font-weight: 700; letter-spacing: 8px;">
                {{ $otp }}
            </h1>
        </div>
    </div>
    
    <table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color: #FEF2F2; border-radius: 12px; margin: 16px 0;">
        <tr>
            <td style="padding: 20px;">
                <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.6; text-align: center;">
                    <strong>This code will expire in 10 minutes</strong>
                </p>
            </td>
        </tr>
    </table>
    
    <p style="margin: 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6; text-align: center;">
        Or click the button below to verify automatically:
    </p>
    
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.partials.button', ['url' => route('auth.verify.otp') . '?code=' . $otp, 'text' => 'Verify Email'])
    </div>
    
    <p style="margin: 24px 0 0 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        If you didn't create an account with IELTS Mock Platform, please ignore this email.
    </p>
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Having trouble?</strong>
    </p>
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        If the button doesn't work, copy and paste this link into your browser:
        <br>
        <a href="{{ route('auth.verify.otp') }}" style="color: #DC2626; word-break: break-all;">{{ route('auth.verify.otp') }}</a>
    </p>
@endsection
