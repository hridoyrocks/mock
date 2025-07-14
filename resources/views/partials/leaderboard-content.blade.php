{{-- resources/views/partials/leaderboard-content.blade.php --}}
@if($leaderboard->isNotEmpty())
    <div class="space-y-3">
        @foreach($leaderboard->take(5) as $entry)
            <div class="flex items-center space-x-3 {{ $entry->user_id === auth()->id() ? 'glass rounded-lg p-2 border-purple-500/50' : '' }}">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br 
                    {{ $loop->iteration === 1 ? 'from-yellow-500 to-amber-500' : 
                       ($loop->iteration === 2 ? 'from-gray-400 to-gray-500' : 
                       ($loop->iteration === 3 ? 'from-orange-500 to-amber-600' : 'from-purple-500 to-pink-500')) }} 
                    flex items-center justify-center text-white font-bold text-sm">
                    {{ $loop->iteration }}
                </div>
                <div class="flex-1">
                    <p class="text-white font-medium text-sm">
                        {{ $entry->user_id === auth()->id() ? 'You' : Str::limit($entry->user->name, 15) }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $entry->total_points }} pts</p>
                </div>
                @if($loop->iteration <= 3)
                    <i class="fas fa-trophy text-{{ $loop->iteration === 1 ? 'yellow' : ($loop->iteration === 2 ? 'gray' : 'orange') }}-400"></i>
                @endif
            </div>
        @endforeach
        
        @if(!$userInLeaderboard)
            <div class="pt-3 border-t border-white/10">
                <p class="text-xs text-gray-400 text-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    You're not in top 5. Keep practicing!
                </p>
            </div>
        @endif
    </div>
@else
    <div class="text-center py-6">
        <i class="fas fa-users text-4xl text-gray-600 mb-3"></i>
        <p class="text-gray-400 text-sm">No leaderboard data yet</p>
        <p class="text-xs text-gray-500 mt-1">Be the first to set a record!</p>
    </div>
@endif