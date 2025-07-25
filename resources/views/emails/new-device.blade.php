@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        New Sign-in from {{ $device->device_name }} 🔐
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $user->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        We detected a new sign-in to your IELTS Mock Platform account. If this was you, you can safely ignore this email.
    </p>
    
    @component('emails.components.info-box')
        <h3 style="margin: 0 0 12px 0; color: #7F1D1D; font-size: 18px; font-weight: 600;">
            Sign-in Details
        </h3>
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Device:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $device->device_name }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Browser:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $device->browser }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Location:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $device->location ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">IP Address:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $device->ip_address }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Time:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $device->created_at->format('M d, Y h:i A') }}</td>
            </tr>
        </table>
    @endcomponent
    
    <div style="background-color: #FEF3C7; border-radius: 12px; padding: 20px; margin: 24px 0;">
        <h3 style="margin: 0 0 8px 0; color: #92400E; font-size: 16px; font-weight: 600;">
            ⚠️ Wasn't you?
        </h3>
        <p style="margin: 0 0 16px 0; color: #92400E; font-size: 14px; line-height: 1.6;">
            If you don't recognize this activity, your account may be compromised. Take action immediately:
        </p>
        <div style="text-align: center;">
            @component('emails.components.button', ['url' => route('password.request')])
                Change Password Now
            @endcomponent
        </div>
    </div>
    
    <p style="margin: 24px 0 0 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        To keep your account secure, we recommend:
    </p>
    <ul style="margin: 8px 0 0 0; padding: 0 0 0 20px; color: #6B7280; font-size: 14px; line-height: 1.8;">
        <li>Using a strong, unique password</li>
        <li>Enabling two-factor authentication</li>
        <li>Reviewing your account activity regularly</li>
    </ul>
@endsection

@section('additional')
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        You can view and manage all your active sessions from your 
        <a href="{{ route('profile.edit') }}" style="color: #DC2626;">Account Settings</a>.
    </p>
@endsection
