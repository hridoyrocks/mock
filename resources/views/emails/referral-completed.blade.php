@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Congratulations! You Earned a Referral Reward 🎉
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $referral->referrer->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Great news! Your friend <strong>{{ $referral->referee->name }}</strong> has successfully joined CD IELTS 
        and completed their first subscription. You've earned a referral reward!
    </p>
    
    @component('emails.components.info-box')
        <div style="text-align: center;">
            <p style="margin: 0 0 8px 0; color: #7F1D1D; font-size: 14px;">
                Your Reward:
            </p>
            <p style="margin: 0; color: #DC2626; font-size: 32px; font-weight: 700;">
                ৳{{ number_format($referral->referrer_reward_amount, 2) }}
            </p>
            <p style="margin: 8px 0 0 0; color: #7F1D1D; font-size: 14px;">
                Added to your referral balance
            </p>
        </div>
    @endcomponent
    
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 18px; font-weight: 600;">
        Your Referral Stats:
    </h3>
    
    <table style="width: 100%; margin: 0 0 32px 0;">
        <tr>
            <td style="padding: 12px; background-color: #FEF2F2; border-radius: 8px; text-align: center; width: 30%;">
                <p style="margin: 0; color: #DC2626; font-size: 20px; font-weight: 700;">{{ $totalReferrals }}</p>
                <p style="margin: 4px 0 0 0; color: #7F1D1D; font-size: 12px;">Total Referrals</p>
            </td>
            <td style="width: 5%;"></td>
            <td style="padding: 12px; background-color: #FEF2F2; border-radius: 8px; text-align: center; width: 30%;">
                <p style="margin: 0; color: #DC2626; font-size: 20px; font-weight: 700;">{{ $successfulReferrals }}</p>
                <p style="margin: 4px 0 0 0; color: #7F1D1D; font-size: 12px;">Successful</p>
            </td>
            <td style="width: 5%;"></td>
            <td style="padding: 12px; background-color: #FEF2F2; border-radius: 8px; text-align: center; width: 30%;">
                <p style="margin: 0; color: #DC2626; font-size: 20px; font-weight: 700;">৳{{ number_format($totalBalance, 2) }}</p>
                <p style="margin: 4px 0 0 0; color: #7F1D1D; font-size: 12px;">Total Balance</p>
            </td>
        </tr>
    </table>
    
    <div style="background-color: #F0FDF4; border-radius: 12px; padding: 20px; margin: 24px 0;">
        <h3 style="margin: 0 0 8px 0; color: #166534; font-size: 16px; font-weight: 600;">
            💡 Did you know?
        </h3>
        <p style="margin: 0; color: #166534; font-size: 14px; line-height: 1.6;">
            You can redeem your referral balance for evaluation tokens or subscription discounts! 
            Minimum redemption amount is ৳50.
        </p>
    </div>
    
    <div style="text-align: center; margin: 32px 0;">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td style="text-align: center;">
                    @component('emails.components.button', ['url' => route('student.referrals.index')])
                        View Referral Dashboard
                    @endcomponent
                </td>
            </tr>
        </table>
    </div>
    
    <div style="background-color: #FEF2F2; border-radius: 12px; padding: 20px; margin: 24px 0; text-align: center;">
        <p style="margin: 0 0 12px 0; color: #7F1D1D; font-size: 16px; font-weight: 600;">
            Keep the momentum going! 🚀
        </p>
        <p style="margin: 0 0 16px 0; color: #4B5563; font-size: 14px;">
            Share your unique referral link and earn more rewards:
        </p>
        <div style="background-color: #FFFFFF; border: 1px dashed #DC2626; border-radius: 8px; padding: 12px; word-break: break-all;">
            <code style="color: #DC2626; font-size: 14px;">{{ $referral->referrer->referral_link }}</code>
        </div>
    </div>
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Referral Terms:</strong>
    </p>
    <ul style="margin: 0; padding: 0 0 0 20px; color: #6B7280; font-size: 13px; line-height: 1.6;">
        <li>Rewards are credited when referred users complete their first subscription</li>
        <li>Minimum ৳50 balance required for redemption</li>
        <li>Referral balance can be used for tokens or subscription discounts</li>
        <li>Cannot be combined with other promotional offers</li>
    </ul>
@endsection
