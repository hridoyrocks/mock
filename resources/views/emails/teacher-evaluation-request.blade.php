@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        New Evaluation Request 📝
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $evaluationRequest->teacher->user->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        You have received a new evaluation request from a student. Please review and provide your feedback by the deadline.
    </p>
    
    @component('emails.components.info-box')
        <h3 style="margin: 0 0 12px 0; color: #7F1D1D; font-size: 18px; font-weight: 600;">
            Request Details
        </h3>
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Student:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $evaluationRequest->studentAttempt->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Test Type:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Test Set:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $evaluationRequest->studentAttempt->testSet->title }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563;">Tokens:</td>
                <td style="padding: 4px 0; color: #1F2937; font-weight: 600; text-align: right;">{{ $evaluationRequest->tokens_paid }} tokens</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #4B5563; vertical-align: top;">Deadline:</td>
                <td style="padding: 4px 0; color: #DC2626; font-weight: 600; text-align: right;">
                    {{ $evaluationRequest->deadline_at->format('M d, Y') }}<br>
                    <small style="color: #7F1D1D; font-weight: normal;">{{ $evaluationRequest->deadline_at->format('h:i A') }}</small>
                </td>
            </tr>
        </table>
    @endcomponent
    
    @if($evaluationRequest->priority === 'urgent')
    <div style="background-color: #FEF3C7; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
        <p style="margin: 0; color: #92400E; font-size: 14px; font-weight: 600;">
            ⚡ This is an URGENT request - Please evaluate as soon as possible
        </p>
    </div>
    @endif
    
    <div style="text-align: center; margin: 32px 0;">
        @php
        $evaluationUrl = isset($evaluationRequest->id) 
                ? route('teacher.evaluations.show', $evaluationRequest->id) 
                        : url('/teacher/evaluations/1');
                @endphp
                @component('emails.components.button', ['url' => $evaluationUrl])
                    Start Evaluation
                @endcomponent
    </div>
    
    <div style="background-color: #F3F4F6; border-radius: 12px; padding: 20px; margin: 24px 0;">
        <h3 style="margin: 0 0 12px 0; color: #374151; font-size: 16px; font-weight: 600;">
            📊 Your Current Stats
        </h3>
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 4px 0; color: #6B7280;">Pending Evaluations:</td>
                <td style="padding: 4px 0; color: #374151; font-weight: 600; text-align: right;">{{ $pendingCount }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #6B7280;">Completed This Month:</td>
                <td style="padding: 4px 0; color: #374151; font-weight: 600; text-align: right;">{{ $completedThisMonth }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #6B7280;">Average Rating:</td>
                <td style="padding: 4px 0; color: #374151; font-weight: 600; text-align: right;">{{ number_format($averageRating, 1) }} ⭐</td>
            </tr>
        </table>
    </div>
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Evaluation Guidelines:</strong>
    </p>
    <ul style="margin: 0; padding: 0 0 0 20px; color: #6B7280; font-size: 14px; line-height: 1.6;">
        <li>Provide detailed, constructive feedback</li>
        <li>Follow IELTS band descriptors accurately</li>
        <li>Complete evaluations before the deadline</li>
        <li>Maintain professional communication</li>
    </ul>
@endsection
