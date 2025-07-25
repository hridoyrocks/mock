@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Your Subscription is Expiring Soon ⏰
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $subscription->user->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Your <strong>{{ $subscription->plan->name }}</strong> subscription will expire in <strong style="color: #DC2626;">{{ $daysRemaining }} days</strong>.
    </p>
    
    @component('emails.components.info-box')
        <div style="text-align: center;">
            <p style="margin: 0 0 8px 0; color: #7F1D1D; font-size: 14px;">
                Expiry Date:
            </p>
            <p style="margin: 0; color: #DC2626; font-size: 24px; font-weight: 700;">
                {{ $subscription->ends_at->format('M d, Y') }}
            </p>
        </div>
    @endcomponent
    
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 20px; font-weight: 600;">
        Don't lose your progress! 📈
    </h3>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Renew now to continue enjoying:
    </p>
    
    <ul style="margin: 0 0 32px 0; padding: 0; list-style: none;">
        <li style="margin: 0 0 8px 0; padding-left: 24px; position: relative; color: #4B5563; font-size: 15px;">
            <span style="position: absolute; left: 0; color: #DC2626;">•</span>
            Unlimited mock tests across all sections
        </li>
        <li style="margin: 0 0 8px 0; padding-left: 24px; position: relative; color: #4B5563; font-size: 15px;">
            <span style="position: absolute; left: 0; color: #DC2626;">•</span>
            AI-powered Writing and Speaking evaluation
        </li>
        <li style="margin: 0 0 8px 0; padding-left: 24px; position: relative; color: #4B5563; font-size: 15px;">
            <span style="position: absolute; left: 0; color: #DC2626;">•</span>
            Detailed performance analytics
        </li>
        <li style="margin: 0 0 8px 0; padding-left: 24px; position: relative; color: #4B5563; font-size: 15px;">
            <span style="position: absolute; left: 0; color: #DC2626;">•</span>
            Access to all your test history
        </li>
    </ul>
    
    <div style="text-align: center; margin: 32px 0;">
        @component('emails.components.button', ['url' => route('subscription.index')])
            Renew Subscription
        @endcomponent
    </div>
    
    @if($subscription->auto_renew)
    <div style="background-color: #F0FDF4; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
        <p style="margin: 0; color: #166534; font-size: 14px;">
            <strong>Auto-renewal is enabled</strong> - Your subscription will automatically renew on {{ $subscription->ends_at->format('M d, Y') }}
        </p>
    </div>
    @endif
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Special Offer!</strong> 🎁
    </p>
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        Renew within the next 48 hours and get <strong>10% off</strong> your next month. 
        Use code: <span style="background-color: #FEE2E2; padding: 2px 8px; border-radius: 4px; font-family: monospace;">RENEW10</span>
    </p>
@endsection
