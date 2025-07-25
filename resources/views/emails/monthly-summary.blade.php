@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Your Monthly Progress Report 📊
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Hi {{ $user->name }},
    </p>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Here's your IELTS preparation summary for {{ $monthName }} {{ $year }}. You're making great progress!
    </p>
    
    {{-- Performance Overview --}}
    <div style="background: linear-gradient(135deg, #FEF3C7 0%, #FEE2E2 100%); border-radius: 12px; padding: 24px; margin: 0 0 32px 0;">
        <h3 style="margin: 0 0 20px 0; color: #7F1D1D; font-size: 20px; font-weight: 600; text-align: center;">
            📈 Performance Overview
        </h3>
        
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; width: 25%;">
                    <div style="background: white; border-radius: 8px; padding: 16px;">
                        <p style="margin: 0; color: #DC2626; font-size: 32px; font-weight: 700;">{{ $testsCompleted }}</p>
                        <p style="margin: 4px 0 0 0; color: #6B7280; font-size: 14px;">Tests Completed</p>
                    </div>
                </td>
                <td style="width: 8px;"></td>
                <td style="text-align: center; width: 25%;">
                    <div style="background: white; border-radius: 8px; padding: 16px;">
                        <p style="margin: 0; color: #DC2626; font-size: 32px; font-weight: 700;">{{ $averageBandScore }}</p>
                        <p style="margin: 4px 0 0 0; color: #6B7280; font-size: 14px;">Avg Band Score</p>
                    </div>
                </td>
                <td style="width: 8px;"></td>
                <td style="text-align: center; width: 25%;">
                    <div style="background: white; border-radius: 8px; padding: 16px;">
                        <p style="margin: 0; color: #DC2626; font-size: 32px; font-weight: 700;">{{ $totalPracticeHours }}h</p>
                        <p style="margin: 4px 0 0 0; color: #6B7280; font-size: 14px;">Practice Time</p>
                    </div>
                </td>
                <td style="width: 8px;"></td>
                <td style="text-align: center; width: 25%;">
                    <div style="background: white; border-radius: 8px; padding: 16px;">
                        <p style="margin: 0; color: #DC2626; font-size: 32px; font-weight: 700;">{{ $streakDays }}</p>
                        <p style="margin: 4px 0 0 0; color: #6B7280; font-size: 14px;">Day Streak</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    {{-- Section Breakdown --}}
    @component('emails.components.info-box')
        <h3 style="margin: 0 0 16px 0; color: #7F1D1D; font-size: 18px; font-weight: 600;">
            Section Performance
        </h3>
        
        <table style="width: 100%; font-size: 14px;">
            @foreach($sectionScores as $section => $data)
            <tr>
                <td style="padding: 8px 0;">
                    <span style="display: inline-block; width: 20px; text-align: center;">{{ $data['icon'] }}</span>
                    <span style="color: #4B5563; margin-left: 8px;">{{ ucfirst($section) }}</span>
                </td>
                <td style="padding: 8px 0; text-align: center;">
                    <span style="color: #1F2937; font-weight: 600;">{{ $data['tests'] }} tests</span>
                </td>
                <td style="padding: 8px 0; text-align: right;">
                    <span style="color: #DC2626; font-weight: 600;">Band {{ $data['average'] }}</span>
                    @if($data['trend'] === 'up')
                        <span style="color: #16A34A; margin-left: 4px;">↑</span>
                    @elseif($data['trend'] === 'down')
                        <span style="color: #DC2626; margin-left: 4px;">↓</span>
                    @else
                        <span style="color: #6B7280; margin-left: 4px;">→</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    @endcomponent
    
    {{-- Achievements --}}
    @if(count($newAchievements) > 0)
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 20px; font-weight: 600;">
        🏆 New Achievements Unlocked
    </h3>
    
    <div style="margin: 0 0 32px 0;">
        @foreach($newAchievements as $achievement)
        <div style="background-color: #FEF2F2; border-radius: 8px; padding: 16px; margin-bottom: 8px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 40px; vertical-align: top;">
                        <span style="font-size: 24px;">{{ $achievement['icon'] }}</span>
                    </td>
                    <td style="padding-left: 12px;">
                        <p style="margin: 0; color: #7F1D1D; font-weight: 600;">{{ $achievement['name'] }}</p>
                        <p style="margin: 4px 0 0 0; color: #6B7280; font-size: 14px;">{{ $achievement['description'] }}</p>
                    </td>
                </tr>
            </table>
        </div>
        @endforeach
    </div>
    @endif
    
    {{-- Recommendations --}}
    <div style="background-color: #F0FDF4; border-radius: 12px; padding: 20px; margin: 24px 0;">
        <h3 style="margin: 0 0 12px 0; color: #166534; font-size: 16px; font-weight: 600;">
            💡 Personalized Recommendations
        </h3>
        <ul style="margin: 0; padding: 0 0 0 20px; color: #166534; font-size: 14px; line-height: 1.8;">
            @foreach($recommendations as $recommendation)
            <li>{{ $recommendation }}</li>
            @endforeach
        </ul>
    </div>
    
    <div style="text-align: center; margin: 32px 0;">
        @component('emails.components.button', ['url' => route('student.dashboard')])
            View Full Dashboard
        @endcomponent
    </div>
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Keep up the great work!</strong> 🚀
    </p>
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        You're in the top {{ $percentile }}% of learners this month. Stay consistent and you'll reach your target band score in no time!
    </p>
@endsection
