@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Scheduled Maintenance Notice 🔧
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $user->name ?? 'there' }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        We'll be performing scheduled maintenance to improve your IELTS Mock Platform experience.
    </p>
    
    @component('emails.components.info-box')
        <div style="text-align: center;">
            <p style="margin: 0 0 8px 0; color: #7F1D1D; font-size: 14px;">
                Maintenance Window:
            </p>
            <p style="margin: 0 0 4px 0; color: #DC2626; font-size: 20px; font-weight: 700;">
                {{ $startTime->format('M d, Y') }}
            </p>
            <p style="margin: 0; color: #7F1D1D; font-size: 16px;">
                {{ $startTime->format('h:i A') }} - {{ $endTime->format('h:i A') }} ({{ $timezone }})
            </p>
            <p style="margin: 12px 0 0 0; color: #6B7280; font-size: 14px;">
                Estimated Duration: {{ $duration }} hours
            </p>
        </div>
    @endcomponent
    
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 18px; font-weight: 600;">
        What to Expect:
    </h3>
    
    <ul style="margin: 0 0 32px 0; padding: 0; list-style: none;">
        <li style="margin: 0 0 12px 0; padding-left: 28px; position: relative; color: #4B5563; font-size: 16px; line-height: 1.6;">
            <span style="position: absolute; left: 0; top: 2px; width: 20px; height: 20px; background-color: #FEE2E2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                <span style="color: #DC2626; font-size: 12px;">1</span>
            </span>
            The platform will be temporarily unavailable during this time
        </li>
        <li style="margin: 0 0 12px 0; padding-left: 28px; position: relative; color: #4B5563; font-size: 16px; line-height: 1.6;">
            <span style="position: absolute; left: 0; top: 2px; width: 20px; height: 20px; background-color: #FEE2E2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                <span style="color: #DC2626; font-size: 12px;">2</span>
            </span>
            All your progress and data will be safely preserved
        </li>
        <li style="margin: 0 0 12px 0; padding-left: 28px; position: relative; color: #4B5563; font-size: 16px; line-height: 1.6;">
            <span style="position: absolute; left: 0; top: 2px; width: 20px; height: 20px; background-color: #FEE2E2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                <span style="color: #DC2626; font-size: 12px;">3</span>
            </span>
            You'll enjoy improved performance and new features after the update
        </li>
    </ul>
    
    <div style="background-color: #FEF3C7; border-radius: 12px; padding: 20px; margin: 24px 0;">
        <h3 style="margin: 0 0 8px 0; color: #92400E; font-size: 16px; font-weight: 600;">
            💡 Pro Tip:
        </h3>
        <p style="margin: 0; color: #92400E; font-size: 14px; line-height: 1.6;">
            Complete any ongoing tests before the maintenance window begins. Incomplete tests will be automatically saved and can be resumed after maintenance.
        </p>
    </div>
    
    @if(isset($improvements) && count($improvements) > 0)
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 18px; font-weight: 600;">
        What's New After Maintenance:
    </h3>
    
    <ul style="margin: 0 0 32px 0; padding: 0 0 0 20px; color: #4B5563; font-size: 15px; line-height: 1.8;">
        @foreach($improvements as $improvement)
        <li>{{ $improvement }}</li>
        @endforeach
    </ul>
    @endif
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Questions?</strong>
    </p>
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        If you have any concerns or need assistance, please contact our support team at 
        <a href="mailto:support@ieltsmock.com" style="color: #DC2626;">support@ieltsmock.com</a>
    </p>
    <p style="margin: 16px 0 0 0; color: #6B7280; font-size: 14px;">
        We apologize for any inconvenience and appreciate your patience.
    </p>
@endsection
