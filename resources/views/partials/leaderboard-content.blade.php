{{-- resources/views/partials/leaderboard-content.blade.php --}}
<div class="space-y-3">
    @if($userInLeaderboard)
        <!-- User's Position -->
        <div class="p-3 gradient-bg text-white rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="font-bold">#{{ $leaderboard->where('user_id', auth()->id())->first()->rank }}</span>
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="font-medium">You</span>
                </div>
                <span class="font-bold">{{ auth()->user()->achievement_points }} pts</span>
            </div>
        </div>
    @endif
    
    <!-- Top Users -->
    <div class="space-y-2">
        @foreach($leaderboard->take(10) as $entry)
            @if($entry->user_id !== auth()->id())
                <div class="flex items-center justify-between p-2 {{ $loop->iteration <= 3 ? 'bg-gray-50 rounded-lg' : '' }}">
                    <div class="flex items-center space-x-3">
                        <span class="font-bold {{ $loop->iteration <= 3 ? 'text-lg' : '' }}">
                            @switch($loop->iteration)
                                @case(1)
                                    <span class="text-yellow-500">🏆</span>
                                    @break
                                @case(2)
                                    <span class="text-gray-400">🥈</span>
                                    @break
                                @case(3)
                                    <span class="text-orange-600">🥉</span>
                                    @break
                                @default
                                    #{{ $entry->rank }}
                            @endswitch
                        </span>
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-sm font-semibold">
                            {{ substr($entry->user->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium">{{ Str::limit($entry->user->name, 15) }}</span>
                    </div>
                    <span class="text-sm font-bold">{{ $entry->total_points }} pts</span>
                </div>
            @endif
        @endforeach
    </div>
    
    @if(!$userInLeaderboard)
        <div class="mt-3 p-3 bg-gray-50 rounded-lg text-center">
            <p class="text-xs text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                You're not in the top 10 yet. Keep practicing!
            </p>
        </div>
    @endif
</div>