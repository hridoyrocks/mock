@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Welcome to IELTS Mock Platform! 🎉
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $user->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Congratulations! You've successfully subscribed to our <strong style="color: #DC2626;">{{ $subscription->plan->name }}</strong> plan. 
        You're now ready to ace your IELTS exam with unlimited practice tests and personalized feedback.
    </p>
    
    <table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color: #FEF2F2; border-radius: 12px; margin: 16px 0;">
        <tr>
            <td style="padding: 20px;">
                <h3 style="margin: 0 0 12px 0; color: #7F1D1D; font-size: 18px; font-weight: 600;">
                    Your Subscription Details
                </h3>
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="padding: 4px 0; color: #4B5563;">Plan:</td>
                        <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $subscription->plan->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #4B5563;">Price:</td>
                        <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">৳{{ number_format($subscription->plan->price, 2) }}/month</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #4B5563;">Valid Until:</td>
                        <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $subscription->ends_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 20px; font-weight: 600;">
        What's Included in Your Plan:
    </h3>
    
    <ul style="margin: 0 0 32px 0; padding: 0; list-style: none;">
        @foreach($subscription->plan->features as $feature)
        <li style="margin: 0 0 12px 0; padding-left: 28px; position: relative; color: #4B5563; font-size: 16px; line-height: 1.6;">
            <span style="position: absolute; left: 0; top: 2px; width: 20px; height: 20px; background-color: #DCFCE7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                <span style="color: #16A34A; font-size: 12px;">✓</span>
            </span>
            {{ $feature->display_name }}
        </li>
        @endforeach
    </ul>
    
    <div style="text-align: center; margin: 32px 0;">
        <table border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td align="center" style="border-radius: 8px; background: linear-gradient(90deg, #DC2626 0%, #EF4444 100%);">
                    <a href="{{ route('student.dashboard') }}" target="_blank" style="display: inline-block; padding: 14px 32px; font-size: 16px; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        Start Practicing Now
                    </a>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('action')
    <h3 style="margin: 0 0 12px 0; color: #374151; font-size: 16px; font-weight: 600;">
        Quick Start Guide:
    </h3>
    <ol style="margin: 0; padding: 0 0 0 20px; color: #4B5563; font-size: 14px; line-height: 1.8;">
        <li>Take a diagnostic test to assess your current level</li>
        <li>Practice with section-specific tests</li>
        <li>Track your progress on the dashboard</li>
        <li>Get AI-powered feedback on Writing and Speaking</li>
    </ol>
@endsection
