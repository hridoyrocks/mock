@if($leaderboard->isNotEmpty())
    <div class="space-y-3">
        @foreach($leaderboard as $entry)
            <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors
                {{ $entry->user_id === auth()->id() ? 'bg-indigo-50 border border-indigo-200' : '' }}">
                <div class="w-8 text-center">
                    @if($entry->rank <= 3)
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            @if($entry->rank === 1) bg-yellow-400
                            @elseif($entry->rank === 2) bg-gray-300
                            @else bg-orange-400
                            @endif">
                            <span class="text-xs font-bold text-white">{{ $entry->rank }}</span>
                        </div>
                    @else
                        <span class="text-sm font-medium text-gray-600">{{ $entry->rank }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 {{ $entry->user_id === auth()->id() ? 'text-indigo-700' : '' }}">
                        {{ $entry->user->name }}
                        @if($entry->user_id === auth()->id())
                            <span class="text-xs text-indigo-600">(You)</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500">{{ $entry->tests_taken }} tests • {{ $entry->total_points }} pts</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-900">{{ number_format($entry->average_score, 1) }}</p>
                    <p class="text-xs text-gray-500">Avg Band</p>
                </div>
            </div>
        @endforeach
    </div>

    @if(!$userInLeaderboard)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg text-center">
            <p class="text-sm text-gray-600">Complete more tests to appear on the leaderboard!</p>
        </div>
    @endif
@else
    <div class="text-center py-8">
        <i class="fas fa-trophy text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-500">No leaderboard data yet</p>
    </div>
@endif