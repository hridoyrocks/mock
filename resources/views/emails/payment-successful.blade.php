@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Payment Successful! 
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $transaction->user->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Your payment has been successfully processed. Thank you for your purchase!
    </p>
    
    @component('emails.components.info-box')
        <h3 style="margin: 0 0 12px 0; color: #7F1D1D; font-size: 18px; font-weight: 600;">
            Transaction Details
        </h3>
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Transaction ID:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $transaction->transaction_id }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Amount:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">৳{{ number_format($transaction->amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Payment Method:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ ucfirst($transaction->payment_method) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Date:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
            </tr>
        </table>
    @endcomponent
    
    @if($transaction->subscriptionPlan)
    <div style="background-color: #F0FDF4; border-radius: 12px; padding: 20px; margin: 24px 0;">
        <h3 style="margin: 0 0 8px 0; color: #166534; font-size: 16px; font-weight: 600;">
            Subscription Activated
        </h3>
        <p style="margin: 0; color: #166534; font-size: 14px;">
            Your {{ $transaction->subscriptionPlan->name }} subscription is now active!
        </p>
    </div>
    @endif
    
    <div style="text-align: center; margin: 32px 0;">
        @component('emails.components.button', ['url' => route('subscription.invoice', $transaction)])
            View Invoice
        @endcomponent
    </div>
    
    <p style="margin: 24px 0 0 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        A copy of your invoice has been attached to this email for your records.
    </p>
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Need help?</strong>
    </p>
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        If you have any questions about your payment or subscription, please contact our support team at 
        <a href="mailto:payment@cdielts.org" style="color: #DC2626;">payment@cdielts.org</a>
    </p>
@endsection
