<div class="teacher-cards">
    @if($teachers->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-user-slash text-6xl text-gray-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">No Teachers Available</h3>
            <p class="text-gray-400">No teachers available for {{ $section }} evaluation at the moment.</p>
        </div>
    @else
        @foreach($teachers as $teacher)
        <div class="glass rounded-xl p-5 hover:border-purple-500/30 transition-all">
            <!-- Teacher Header -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    @if($teacher->user->avatar_url)
                        <img src="{{ $teacher->user->avatar_url }}" 
                             alt="{{ $teacher->user->name }}" 
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($teacher->user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="text-white font-medium">{{ $teacher->user->name }}</h4>
                        <div class="flex items-center space-x-2">
                            <span class="text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $teacher->rating ? '' : 'opacity-30' }} text-xs"></i>
                                @endfor
                            </span>
                            <span class="text-xs text-gray-400">({{ $teacher->total_evaluations_done }})</span>
                        </div>
                    </div>
                </div>
                @if($teacher->rating >= 4.5)
                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">
                        <i class="fas fa-crown"></i>
                    </span>
                @endif
            </div>
            
            <!-- Quick Stats -->
            <div class="flex items-center justify-between text-xs text-gray-400 mb-3">
                <span><i class="fas fa-clock mr-1"></i>{{ $teacher->average_turnaround_hours }}h avg</span>
                <span><i class="fas fa-briefcase mr-1"></i>{{ $teacher->experience_years }}y exp</span>
            </div>
            
            <!-- Pricing -->
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="glass rounded-lg p-2 text-center">
                    <p class="text-xs text-gray-400 mb-1">Normal</p>
                    <p class="font-bold text-white">
                        <i class="fas fa-coins text-yellow-400 text-xs mr-1"></i>{{ $teacher->token_price }}
                    </p>
                </div>
                <div class="glass rounded-lg p-2 text-center border-orange-500/30">
                    <p class="text-xs text-gray-400 mb-1">Urgent</p>
                    <p class="font-bold text-white">
                        <i class="fas fa-coins text-yellow-400 text-xs mr-1"></i>{{ $teacher->urgent_price }}
                    </p>
                </div>
            </div>
            
            <!-- Action Button -->
            @if($tokenBalance && $tokenBalance->available_tokens >= $teacher->token_price)
                <button onclick="selectTeacher({{ $teacher->id }}, '{{ $teacher->user->name }}', 'normal')" 
                        class="w-full py-2 rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
                    Select Teacher
                </button>
            @else
                <div class="text-center">
                    <p class="text-xs text-red-400 mb-1">Need {{ $teacher->token_price }} tokens</p>
                    <a href="{{ route('student.tokens.purchase') }}" target="_blank"
                       class="text-xs text-purple-400 hover:text-purple-300 underline">
                        Buy Tokens
                    </a>
                </div>
            @endif
        </div>
        @endforeach
    @endif
</div>
