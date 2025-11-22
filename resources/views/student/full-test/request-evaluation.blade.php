{{-- resources/views/student/full-test/request-evaluation.blade.php --}}
<x-student-layout>
    <x-slot:title>Request Full Test Evaluation</x-slot>

    <style>
        @keyframes rotate-border {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .animated-border {
            position: relative;
            background: linear-gradient(90deg, #C8102E, #FFD700, #C8102E, #FFD700);
            background-size: 300% 300%;
            animation: rotate-border 3s ease infinite;
            padding: 2px;
            border-radius: 0.5rem;
        }

        .animated-border-yellow {
            position: relative;
            background: linear-gradient(90deg, #EAB308, #FCD34D, #EAB308, #FCD34D);
            background-size: 300% 300%;
            animation: rotate-border 3s ease infinite;
            padding: 2px;
            border-radius: 0.5rem;
        }

        .border-content {
            background: rgba(17, 24, 39, 0.8);
            border-radius: 0.4rem;
        }
    </style>

    <section class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('student.full-test.results', $fullTestAttempt) }}"
                   class="inline-flex items-center text-sm text-gray-400 hover:text-white mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Results
                </a>

                <h1 class="text-2xl font-bold text-white mb-2">Request Human Evaluation</h1>
                <p class="text-gray-400">{{ $fullTestAttempt->fullTest->title }}</p>
            </div>

            <!-- Token Balance -->
            <div class="glass-dark border border-white/10 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-400">Your Token Balance</p>
                            <p class="text-xl font-bold text-white">{{ number_format($tokenBalance->available_tokens) }} Tokens</p>
                        </div>
                    </div>
                    <a href="{{ route('student.tokens.purchase') }}"
                       class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium rounded-lg transition-colors text-sm">
                        Buy Tokens
                    </a>
                </div>
            </div>

            <!-- Teacher Selection -->
            @if($teachers->count() > 0)
                <form action="{{ route('student.full-test.submit-evaluation', $fullTestAttempt) }}" method="POST">
                    @csrf

                    <!-- Select Sections to be Evaluated -->
                    <div class="glass-dark border border-white/10 rounded-xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Select Sections to Evaluate</h2>
                        <p class="text-sm text-gray-400 mb-4">Choose which sections you want to get evaluated. You can select one or both.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="sectionSelection">
                            @foreach($sectionsNeedingEvaluation as $index => $section)
                                <label class="cursor-pointer block section-option" data-section="{{ $section['type'] }}">
                                    <input type="checkbox"
                                           name="sections[]"
                                           value="{{ $section['student_attempt']->id }}"
                                           class="hidden section-checkbox"
                                           data-type="{{ $section['type'] }}">
                                    <div class="section-border rounded-lg transition-all hover:scale-[1.02]">
                                        <div class="glass border-2 border-white/10 rounded-lg p-4 border-content">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-[#C8102E]/10 flex items-center justify-center mr-3">
                                                        <i class="fas {{ $section['type'] === 'writing' ? 'fa-pen-fancy' : 'fa-microphone' }} text-[#C8102E]"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-white capitalize">{{ $section['type'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="section-check-icon hidden">
                                                    <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-4 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-coins text-yellow-400 mr-2"></i>
                                    <span class="text-sm text-gray-300">Total Cost:</span>
                                </div>
                                <span id="totalCost" class="text-lg font-bold text-yellow-400">0 tokens</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                10 tokens per section (15 tokens for urgent priority)
                            </p>
                        </div>
                    </div>

                    <!-- Priority Selection -->
                    <div class="glass-dark border border-white/10 rounded-xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Select Priority</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="prioritySelection">
                            <label class="cursor-pointer block priority-option" data-priority="normal">
                                <input type="radio" name="priority" value="normal" class="hidden priority-radio" checked>
                                <div class="priority-border animated-border rounded-lg transition-all hover:scale-[1.02]">
                                    <div class="glass border-2 border-transparent rounded-lg p-4 border-content">
                                        <div class="flex items-start justify-between mb-2">
                                            <h3 class="font-semibold text-white">Normal</h3>
                                            <span class="text-sm text-gray-400">48 hours</span>
                                        </div>
                                        <p class="text-sm text-gray-400">Standard evaluation turnaround</p>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer block priority-option" data-priority="urgent">
                                <input type="radio" name="priority" value="urgent" class="hidden priority-radio">
                                <div class="priority-border rounded-lg transition-all hover:scale-[1.02]">
                                    <div class="glass border-2 border-white/10 rounded-lg p-4 border-content">
                                        <div class="flex items-start justify-between mb-2">
                                            <h3 class="font-semibold text-white">Urgent</h3>
                                            <span class="text-sm text-yellow-400">12 hours</span>
                                        </div>
                                        <p class="text-sm text-gray-400">Priority evaluation (1.5x cost)</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Teacher Cards -->
                    <div class="glass-dark border border-white/10 rounded-xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Select Teacher</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="teacherSelection">
                            @foreach($teachers as $teacher)
                                <label class="cursor-pointer block teacher-option" data-teacher="{{ $teacher->id }}">
                                    <input type="radio" name="teacher_id" value="{{ $teacher->id }}" class="hidden teacher-radio" required>
                                    <div class="teacher-border rounded-lg h-full transition-all hover:scale-[1.02]">
                                        <div class="glass border-2 border-white/10 rounded-lg p-4 h-full border-content">
                                            <div class="flex items-start mb-3">
                                                <img src="{{ $teacher->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name) }}"
                                                     alt="{{ $teacher->user->name }}"
                                                     class="w-12 h-12 rounded-full mr-3">
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-white">{{ $teacher->user->name }}</h3>
                                                    @if($teacher->specialization)
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($teacher->specialization as $spec)
                                                                <span class="text-xs px-2 py-0.5 bg-white/10 rounded text-gray-300 capitalize">{{ $spec }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($teacher->bio)
                                                <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ $teacher->bio }}</p>
                                            @endif
                                            <div class="flex items-center justify-between pt-3 border-t border-white/10">
                                                <span class="text-xs text-gray-400">Cost per section</span>
                                                <span class="font-semibold text-yellow-400">10 tokens</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-3 bg-[#C8102E] hover:bg-[#A00D24] text-white font-medium rounded-lg transition-colors">
                            Request Evaluation
                        </button>
                    </div>
                </form>
            @else
                <div class="glass-dark border border-yellow-500/30 rounded-xl p-6 text-center">
                    <svg class="w-12 h-12 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">No Teachers Available</h3>
                    <p class="text-gray-400 mb-4">There are currently no teachers available for evaluation. Please try again later.</p>
                    <a href="{{ route('student.full-test.results', $fullTestAttempt) }}"
                       class="inline-block px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                        Back to Results
                    </a>
                </div>
            @endif

        </div>
    </section>

    <script>
        // Section, priority, and teacher selection handler
        document.addEventListener('DOMContentLoaded', function() {
            // Function to calculate and update total cost
            function updateTotalCost() {
                const checkedSections = document.querySelectorAll('.section-checkbox:checked');
                const priorityRadio = document.querySelector('.priority-radio:checked');
                const isUrgent = priorityRadio && priorityRadio.value === 'urgent';

                const tokensPerSection = isUrgent ? 15 : 10;
                const totalCost = checkedSections.length * tokensPerSection;

                const totalCostElement = document.getElementById('totalCost');
                totalCostElement.textContent = totalCost + ' tokens';
            }

            // Handle section selection (checkboxes)
            const sectionOptions = document.querySelectorAll('.section-option');

            sectionOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const checkbox = this.querySelector('.section-checkbox');
                    const border = this.querySelector('.section-border');
                    const innerDiv = this.querySelector('.glass');
                    const checkIcon = this.querySelector('.section-check-icon');

                    // Toggle checkbox
                    checkbox.checked = !checkbox.checked;

                    // Update visual state
                    if (checkbox.checked) {
                        border.classList.add('animated-border');
                        innerDiv.classList.add('border-transparent');
                        innerDiv.classList.remove('border-white/10');
                        checkIcon.classList.remove('hidden');
                    } else {
                        border.classList.remove('animated-border');
                        innerDiv.classList.remove('border-transparent');
                        innerDiv.classList.add('border-white/10');
                        checkIcon.classList.add('hidden');
                    }

                    // Update total cost
                    updateTotalCost();
                });
            });

            // Handle priority selection
            const priorityOptions = document.querySelectorAll('.priority-option');

            priorityOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove animated border from all options
                    priorityOptions.forEach(opt => {
                        const border = opt.querySelector('.priority-border');
                        const innerDiv = opt.querySelector('.glass');
                        border.classList.remove('animated-border', 'animated-border-yellow');
                        innerDiv.classList.remove('border-transparent');
                        innerDiv.classList.add('border-white/10');
                    });

                    // Add animated border to selected option
                    const border = this.querySelector('.priority-border');
                    const innerDiv = this.querySelector('.glass');
                    const priority = this.dataset.priority;

                    if (priority === 'normal') {
                        border.classList.add('animated-border');
                    } else {
                        border.classList.add('animated-border-yellow');
                    }

                    innerDiv.classList.add('border-transparent');
                    innerDiv.classList.remove('border-white/10');

                    // Check the radio button
                    const radio = this.querySelector('.priority-radio');
                    radio.checked = true;

                    // Update total cost
                    updateTotalCost();
                });
            });

            // Handle teacher selection
            const teacherOptions = document.querySelectorAll('.teacher-option');

            teacherOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove animated border from all options
                    teacherOptions.forEach(opt => {
                        const border = opt.querySelector('.teacher-border');
                        const innerDiv = opt.querySelector('.glass');
                        border.classList.remove('animated-border');
                        innerDiv.classList.remove('border-transparent');
                        innerDiv.classList.add('border-white/10');
                    });

                    // Add animated border to selected option
                    const border = this.querySelector('.teacher-border');
                    const innerDiv = this.querySelector('.glass');
                    border.classList.add('animated-border');
                    innerDiv.classList.add('border-transparent');
                    innerDiv.classList.remove('border-white/10');

                    // Check the radio button
                    const radio = this.querySelector('.teacher-radio');
                    radio.checked = true;
                });
            });

            // Set default selection on page load
            const defaultPriority = document.querySelector('.priority-option[data-priority="normal"]');
            if (defaultPriority) {
                defaultPriority.click();
            }

            // Initialize total cost display on page load
            updateTotalCost();

            // Form validation - ensure at least one section is selected
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const checkedSections = document.querySelectorAll('.section-checkbox:checked');

                    if (checkedSections.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one section to evaluate.');
                        return false;
                    }
                });
            }
        });
    </script>
</x-student-layout>
