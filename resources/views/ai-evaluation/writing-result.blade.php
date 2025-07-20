<x-student-layout>
    <x-slot:title>AI Writing Evaluation</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                
                <!-- Test Info Header -->
                <div class="glass rounded-2xl p-8">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                                <i class="fas fa-robot text-purple-400 mr-3"></i>
                                Instant Writing Evaluation
                            </h1>
                            <p class="text-gray-300">
                                {{ $attempt->testSet->title }} • {{ $attempt->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <!-- AI Score -->
                            <div class="glass rounded-xl px-8 py-6 text-center border-purple-500/30">
                                <p class="text-gray-400 text-sm mb-1">Overall Band Score</p>
                                <p class="text-5xl font-bold text-white">
                                    {{ number_format($evaluation['overall_band'], 1) }}
                                </p>
                                <div class="mt-2 text-xs text-purple-400">AI Generated</div>
                            </div>
                            
                            <!-- Human Evaluation Button -->
                            <div class="flex items-center">
                                <button onclick="openTeacherModal()" 
                                        class="glass rounded-xl px-6 py-3 hover:border-blue-500/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fas fa-user-tie text-white"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-white font-semibold">Get Human Evaluation</p>
                                            <p class="text-gray-400 text-xs">By certified IELTS teachers</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-7xl mx-auto">
            <!-- Task Results -->
            @foreach($evaluation['tasks'] as $index => $task)
            <div class="glass rounded-2xl p-6 mb-8">
                <!-- Task Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 mr-3">
                            {{ $index + 1 }}
                        </span>
                        {{ $task['question_title'] }}
                    </h2>
                    <div class="glass rounded-lg px-4 py-2 border-purple-500/30">
                        <span class="text-2xl font-bold text-white">{{ number_format($task['band_score'], 1) }}</span>
                    </div>
                </div>

                <!-- Word Count Badge -->
                <div class="mb-6">
                    <span class="glass px-4 py-2 rounded-lg inline-flex items-center
                           {{ $task['word_count'] >= $task['required_words'] ? 'border-green-500/30' : 'border-red-500/30' }}">
                        <i class="fas fa-file-word mr-2 {{ $task['word_count'] >= $task['required_words'] ? 'text-green-400' : 'text-red-400' }}"></i>
                        <span class="text-gray-300">Word Count: </span>
                        <span class="ml-2 font-semibold {{ $task['word_count'] >= $task['required_words'] ? 'text-green-400' : 'text-red-400' }}">
                            {{ $task['word_count'] }} / {{ $task['required_words'] }}
                        </span>
                    </span>
                </div>

                <!-- Criteria Scores Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    @foreach($task['criteria'] as $criterion => $score)
                    <div class="glass rounded-xl p-6 text-center hover:border-purple-500/50 transition-all">
                        <p class="text-gray-400 text-sm mb-2">{{ $criterion }}</p>
                        <p class="text-3xl font-bold 
                           {{ $score >= 7 ? 'text-green-400' : ($score >= 5 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ number_format($score, 1) }}
                        </p>
                        <div class="mt-3 w-full h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-1000"
                                 style="width: {{ ($score/9)*100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Detailed Feedback Grid -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <!-- Task Achievement -->
                    <div class="glass rounded-xl p-5 border-l-4 border-blue-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-bullseye text-blue-400 mr-2"></i>
                            Task Achievement
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $task['feedback']['task_achievement'] }}</p>
                    </div>

                    <!-- Coherence and Cohesion -->
                    <div class="glass rounded-xl p-5 border-l-4 border-green-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-link text-green-400 mr-2"></i>
                            Coherence and Cohesion
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $task['feedback']['coherence_cohesion'] }}</p>
                    </div>

                    <!-- Lexical Resource -->
                    <div class="glass rounded-xl p-5 border-l-4 border-yellow-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-book text-yellow-400 mr-2"></i>
                            Lexical Resource
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $task['feedback']['lexical_resource'] }}</p>
                        @if(!empty($task['vocabulary_suggestions']))
                        <div class="mt-4">
                            <p class="text-xs text-gray-400 mb-2">Vocabulary improvements:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($task['vocabulary_suggestions'] as $suggestion)
                                <span class="glass px-2 py-1 rounded text-xs border-yellow-500/30">
                                    <span class="text-yellow-400">{{ $suggestion['original'] }}</span>
                                    <i class="fas fa-arrow-right mx-1 text-gray-500"></i>
                                    <span class="text-green-400">{{ $suggestion['suggested'] }}</span>
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Grammar -->
                    <div class="glass rounded-xl p-5 border-l-4 border-red-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-spell-check text-red-400 mr-2"></i>
                            Grammatical Range and Accuracy
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $task['feedback']['grammar'] }}</p>
                        @if(!empty($task['grammar_errors']))
                        <div class="mt-4">
                            <p class="text-xs text-gray-400 mb-2">Grammar issues:</p>
                            <ul class="space-y-1">
                                @foreach($task['grammar_errors'] as $error)
                                <li class="text-xs text-gray-300 flex items-start">
                                    <i class="fas fa-circle text-red-400 mr-2 mt-1" style="font-size: 4px;"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Improvement Tips -->
                @if(!empty($task['improvement_tips']))
                <div class="glass rounded-xl p-5 bg-purple-500/10 border-purple-500/30 mb-6">
                    <h3 class="font-semibold text-white mb-3 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                        Tips for Improvement
                    </h3>
                    <ul class="space-y-2">
                        @foreach($task['improvement_tips'] as $tip)
                        <li class="flex items-start text-sm text-gray-300">
                            <i class="fas fa-check-circle text-purple-400 mr-2 mt-0.5"></i>
                            {{ $tip }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Show/Hide Essay -->
                <button onclick="toggleEssay({{ $index }})" 
                        class="flex items-center justify-between w-full glass rounded-xl p-4 hover:border-purple-500/50 transition-all">
                    <h3 class="font-medium text-white">
                        <i class="fas fa-file-alt text-purple-400 mr-2"></i>
                        View Your Essay
                    </h3>
                    <i class="fas fa-chevron-down text-purple-400 transition-transform" id="essay-chevron-{{ $index }}"></i>
                </button>
                <div id="essay-{{ $index }}" class="hidden mt-4 glass rounded-xl p-5 bg-blue-500/10 border-blue-500/30">
                    <h4 class="font-semibold text-white mb-3">Your Response:</h4>
                    <div class="text-gray-300 whitespace-pre-wrap text-sm leading-relaxed">{{ $task['essay_text'] }}</div>
                </div>
            </div>
            @endforeach

            <!-- Overall Summary -->
            <div class="glass rounded-2xl p-8 bg-gradient-to-br from-purple-600/20 to-pink-600/20 border-purple-500/30 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-chart-line text-purple-400 mr-2"></i>
                    Overall Performance Summary
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Strengths -->
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            Your Strengths
                        </h3>
                        <ul class="space-y-3">
                            @foreach($evaluation['overall_strengths'] as $strength)
                            <li class="flex items-start text-gray-300">
                                <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5"></i>
                                {{ $strength }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Areas for Improvement -->
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-line text-blue-400 mr-2"></i>
                            Areas for Improvement
                        </h3>
                        <ul class="space-y-3">
                            @foreach($evaluation['overall_improvements'] as $improvement)
                            <li class="flex items-start text-gray-300">
                                <i class="fas fa-arrow-up text-purple-400 mr-3 mt-0.5"></i>
                                {{ $improvement }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Centered) -->
            <div class="flex justify-center">
                <a href="{{ route('student.writing.index') }}" 
                   class="px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
                    <i class="fas fa-redo mr-2"></i> Practice Again
                </a>
            </div>
        </div>
    </section>

    <!-- Teacher Selection Modal -->
    <div id="teacherModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="glass rounded-2xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-user-tie text-blue-400 mr-2"></i>
                    Select a Teacher for Human Evaluation
                </h2>
                <button onclick="closeTeacherModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="teacherList" class="space-y-4">
                <!-- Teachers will be loaded here via AJAX -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-400"></i>
                    <p class="text-gray-400 mt-4">Loading available teachers...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function toggleEssay(index) {
        const essay = document.getElementById(`essay-${index}`);
        const chevron = document.getElementById(`essay-chevron-${index}`);
        essay.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
    
    function openTeacherModal() {
        document.getElementById('teacherModal').classList.remove('hidden');
        loadTeachers();
    }
    
    function closeTeacherModal() {
        document.getElementById('teacherModal').classList.add('hidden');
    }
    
    function loadTeachers() {
        fetch(`/student/human-evaluation/{{ $attempt->id }}/teachers`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const teacherCards = doc.querySelector('.teacher-cards');
                
                if (teacherCards) {
                    document.getElementById('teacherList').innerHTML = teacherCards.innerHTML;
                } else {
                    document.getElementById('teacherList').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-red-400 text-4xl mb-4"></i>
                            <p class="text-gray-400">No teachers available at the moment.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading teachers:', error);
                document.getElementById('teacherList').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-red-400 text-4xl mb-4"></i>
                        <p class="text-gray-400">Failed to load teachers. Please try again.</p>
                    </div>
                `;
            });
    }
    
    function selectTeacher(teacherId, teacherName, priority = 'normal') {
        if (confirm(`Request evaluation from ${teacherName} (${priority} priority)?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/student/human-evaluation/{{ $attempt->id }}/request`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const teacherInput = document.createElement('input');
            teacherInput.type = 'hidden';
            teacherInput.name = 'teacher_id';
            teacherInput.value = teacherId;
            form.appendChild(teacherInput);
            
            const priorityInput = document.createElement('input');
            priorityInput.type = 'hidden';
            priorityInput.name = 'priority';
            priorityInput.value = priority;
            form.appendChild(priorityInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
    @endpush
    
    @push('styles')
    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
    @endpush
</x-student-layout>