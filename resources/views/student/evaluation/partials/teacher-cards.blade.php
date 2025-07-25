<div class="teacher-cards">
    <!-- Token Balance Header -->
    @if($tokenBalance)
    <div class="glass rounded-lg p-3 mb-4 bg-gradient-to-r from-purple-600/20 to-pink-600/20 border-purple-500/30">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-300">Your Token Balance:</span>
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-white">
                    <i class="fas fa-coins text-yellow-400 text-lg"></i>
                    {{ $tokenBalance->available_tokens }}
                </span>
                <a href="{{ route('student.tokens.purchase') }}" 
                   class="text-xs glass px-3 py-1 rounded-full hover:border-purple-500/50 transition">
                    <i class="fas fa-plus mr-1"></i>Add
                </a>
            </div>
        </div>
    </div>
    @endif

    @if($teachers->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-user-slash text-5xl text-gray-600 mb-4"></i>
            <h3 class="text-lg font-medium text-white mb-2">No Teachers Available</h3>
            <p class="text-gray-400 text-sm">No teachers available for {{ $section }} evaluation at the moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($teachers as $teacher)
            <div class="glass rounded-lg p-4 hover:border-purple-500/50 transition-all transform hover:scale-[1.02]">
                <!-- Teacher Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        @if($teacher->user->avatar_url ?? false)
                            <img src="{{ $teacher->user->avatar_url }}" 
                                 alt="{{ $teacher->user->name }}" 
                                 class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xs">
                                {{ substr($teacher->user->name ?? 'T', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="text-sm font-medium text-white">{{ $teacher->user->name ?? 'Unknown Teacher' }}</h4>
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= ($teacher->rating ?? 0) ? '' : 'opacity-30' }}" style="font-size: 10px;"></i>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-400">({{ $teacher->total_evaluations_done ?? 0 }})</span>
                            </div>
                        </div>
                    </div>
                    @if(($teacher->rating ?? 0) >= 4.5)
                        <span class="px-1.5 py-0.5 bg-yellow-500/20 text-yellow-400 text-xs rounded-full" title="Top Rated">
                            <i class="fas fa-crown" style="font-size: 10px;"></i>
                        </span>
                    @endif
                </div>
                
                <!-- Stats Bar -->
                <div class="flex items-center justify-between text-xs text-gray-400 mb-3 px-1">
                    <span title="Average Response Time"><i class="fas fa-clock mr-1"></i>{{ $teacher->average_turnaround_hours ?? 24 }}h</span>
                    <span title="Experience"><i class="fas fa-medal mr-1"></i>{{ $teacher->experience_years ?? 0 }}y</span>
                    @if($teacher->specialization && is_array($teacher->specialization) && in_array('IELTS Expert', $teacher->specialization))
                        <span class="text-purple-400" title="IELTS Specialist"><i class="fas fa-certificate"></i></span>
                    @endif
                </div>
                
                <!-- Pricing Buttons -->
                <div class="space-y-2">
                    @if($tokenBalance && $tokenBalance->available_tokens >= ($teacher->token_price ?? 0))
                        <button onclick="selectTeacher({{ $teacher->id }}, '{{ addslashes($teacher->user->name ?? 'Unknown') }}', 'normal')" 
                                class="w-full py-2 rounded-lg bg-gradient-to-r from-purple-600/80 to-purple-700/80 text-white text-sm font-medium hover:from-purple-600 hover:to-purple-700 transition-all flex items-center justify-between px-3 group">
                            <span>Normal (48h)</span>
                            <span class="flex items-center">
                                <i class="fas fa-coins text-yellow-400 text-xs mr-1"></i>
                                <span class="font-bold">{{ $teacher->token_price ?? 0 }}</span>
                                <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                    @else
                        <button disabled 
                                class="w-full py-2 rounded-lg bg-gray-800/50 text-gray-500 text-sm font-medium cursor-not-allowed flex items-center justify-between px-3">
                            <span>Normal (48h)</span>
                            <span class="flex items-center">
                                <i class="fas fa-coins text-gray-500 text-xs mr-1"></i>
                                <span class="font-bold">{{ $teacher->token_price ?? 0 }}</span>
                            </span>
                        </button>
                    @endif
                    
                    @if($tokenBalance && $tokenBalance->available_tokens >= ($teacher->urgent_price ?? 0))
                        <button onclick="selectTeacher({{ $teacher->id }}, '{{ addslashes($teacher->user->name ?? 'Unknown') }}', 'urgent')" 
                                class="w-full py-1.5 rounded-lg glass border-orange-500/30 text-orange-400 text-xs font-medium hover:bg-orange-500/10 transition-all flex items-center justify-between px-3">
                            <span><i class="fas fa-bolt mr-1"></i>Urgent (12h)</span>
                            <span class="flex items-center">
                                <i class="fas fa-coins text-yellow-400 mr-1"></i>
                                <span class="font-bold">{{ $teacher->urgent_price ?? 0 }}</span>
                            </span>
                        </button>
                    @else
                        <button disabled 
                                class="w-full py-1.5 rounded-lg glass border-gray-700/30 text-gray-500 text-xs font-medium cursor-not-allowed flex items-center justify-between px-3">
                            <span><i class="fas fa-bolt mr-1"></i>Urgent (12h)</span>
                            <span class="flex items-center">
                                <i class="fas fa-coins text-gray-500 mr-1"></i>
                                <span class="font-bold">{{ $teacher->urgent_price ?? 0 }}</span>
                            </span>
                        </button>
                    @endif
                </div>
                
                @if($tokenBalance && $tokenBalance->available_tokens < ($teacher->token_price ?? 0))
                    <p class="text-xs text-center text-red-400 mt-2">
                        Need {{ ($teacher->token_price ?? 0) - $tokenBalance->available_tokens }} more tokens
                    </p>
                @endif
            </div>
            @endforeach
        </div>
    @endif
</div>
