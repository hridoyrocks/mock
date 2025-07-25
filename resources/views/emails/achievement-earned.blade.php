@extends('emails.layouts.master')

@section('content')
    <h2 style="margin: 0 0 24px 0; color: #1F2937; font-size: 28px; font-weight: 700;">
        Achievement Unlocked! 🏆
    </h2>
    
    <p style="margin: 0 0 24px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        Congratulations {{ $achievement->user->name }}!
    </p>
    
    <p style="margin: 0 0 32px 0; color: #4B5563; font-size: 16px; line-height: 1.6;">
        You've earned a new achievement badge for your outstanding performance!
    </p>
    
    <div style="text-align: center; margin: 32px 0;">
        <div style="display: inline-block; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border-radius: 50%; width: 120px; height: 120px; padding: 4px;">
            <div style="background-color: #FFFFFF; border-radius: 50%; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 60px;">{{ $achievement->badge->icon }}</span>
            </div>
        </div>
    </div>
    
    @component('emails.components.info-box')
        <div style="text-align: center;">
            <h3 style="margin: 0 0 8px 0; color: #7F1D1D; font-size: 20px; font-weight: 700;">
                {{ $achievement->badge->name }}
            </h3>
            <p style="margin: 0 0 12px 0; color: #4B5563; font-size: 16px;">
                {{ $achievement->badge->description }}
            </p>
            <p style="margin: 0; color: #6B7280; font-size: 14px;">
                Earned on: {{ $achievement->created_at->format('M d, Y') }}
            </p>
        </div>
    @endcomponent
    
    <h3 style="margin: 32px 0 16px 0; color: #1F2937; font-size: 18px; font-weight: 600;">
        Your Achievement Stats:
    </h3>
    
    <table style="width: 100%; margin: 0 0 32px 0;">
        <tr>
            <td style="padding: 12px; background-color: #FEF2F2; border-radius: 8px; text-align: center; width: 33%;">
                <p style="margin: 0; color: #DC2626; font-size: 24px; font-weight: 700;">{{ $totalAchievements }}</p>
                <p style="margin: 4px 0 0 0; color: #7F1D1D; font-size: 14px;">Total Badges</p>
            </td>
            <td style="width: 16px;"></td>
            <td style="padding: 12px; background-color: #FEF2F2; border-radius: 8px; text-align: center; width: 33%;">
                <p style="margin: 0; color: #DC2626; font-size: 24px; font-weight: 700;">{{ $recentStreak }}</p>
                <p style="margin: 4px 0 0 0; color: #7F1D1D; font-size: 14px;">Day Streak</p>
            </td>
            <td style="width: 16px;"></td>
            <td style="padding: 12px; background-color: #FEF2F2; border-radius: 8px; text-align: center; width: 33%;">
                <p style="margin: 0; color: #DC2626; font-size: 24px; font-weight: 700;">{{ $leaderboardPosition }}</p>
                <p style="margin: 4px 0 0 0; color: #7F1D1D; font-size: 14px;">Leaderboard</p>
            </td>
        </tr>
    </table>
    
    <div style="text-align: center; margin: 32px 0;">
        @component('emails.components.button', ['url' => route('student.dashboard')])
            View All Achievements
        @endcomponent
    </div>
@endsection

@section('additional')
    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
        <strong>Keep going!</strong> 🚀
    </p>
    <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6;">
        You're making great progress. Check out your dashboard to see what achievements you can unlock next!
    </p>
@endsection
