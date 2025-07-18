{{-- resources/views/student/full-test/onboarding.blade.php --}}
<x-student-layout>
    <x-slot:title>{{ $fullTest->title }} - Get Ready</x-slot>

    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl w-full">
            <!-- Progress Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                            1
                        </div>
                        <span class="ml-2 text-white font-medium">Get Ready</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 font-bold">
                            2
                        </div>
                        <span class="ml-2 text-gray-400">Take Test</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 font-bold">
                            3
                        </div>
                        <span class="ml-2 text-gray-400">View Results</span>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="glass rounded-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mx-auto mb-6 neon-purple">
                        <i class="fas fa-file-alt text-white text-4xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $fullTest->title }}</h1>
                    @if($fullTest->description)
                        <p class="text-gray-400">{{ $fullTest->description }}</p>
                    @endif
                </div>

                <!-- Test Information -->
                <div class="space-y-4 mb-6">
                    <!-- Test Structure -->
                    <div class="bg-gray-800/50 rounded-xl p-4">
                        <h3 class="text-base font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-clock text-blue-400 mr-2"></i>
                            Test Duration
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="text-center p-2 bg-gray-900/50 rounded-lg">
                                <i class="fas fa-headphones text-violet-400 mb-1"></i>
                                <p class="text-xs text-gray-400">Listening</p>
                                <p class="text-sm text-white font-medium">30 min</p>
                            </div>
                            <div class="text-center p-2 bg-gray-900/50 rounded-lg">
                                <i class="fas fa-book-open text-emerald-400 mb-1"></i>
                                <p class="text-xs text-gray-400">Reading</p>
                                <p class="text-sm text-white font-medium">60 min</p>
                            </div>
                            <div class="text-center p-2 bg-gray-900/50 rounded-lg">
                                <i class="fas fa-pen-fancy text-amber-400 mb-1"></i>
                                <p class="text-xs text-gray-400">Writing</p>
                                <p class="text-sm text-white font-medium">60 min</p>
                            </div>
                            <div class="text-center p-2 bg-gray-900/50 rounded-lg">
                                <i class="fas fa-microphone text-rose-400 mb-1"></i>
                                <p class="text-xs text-gray-400">Speaking</p>
                                <p class="text-sm text-white font-medium">15 min</p>
                            </div>
                        </div>
                        <p class="text-center text-indigo-400 text-sm mt-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Total: ~3 hours (with breaks)
                        </p>
                    </div>

                    <!-- Quick Notes -->
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4">
                        <h3 class="text-sm font-semibold text-amber-400 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Quick Reminders
                        </h3>
                        <ul class="space-y-1 text-xs text-gray-300">
                            <li class="flex items-start">
                                <i class="fas fa-check text-amber-400 mt-0.5 mr-2"></i>
                                <span>Complete sections in order • Take breaks between sections</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-amber-400 mt-0.5 mr-2"></i>
                                <span>Each section timer cannot be paused • Progress auto-saves</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Checklist -->
                    <div class="bg-gray-800/50 rounded-xl p-4">
                        <h3 class="text-sm font-semibold text-white mb-3">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            Ready to Start?
                        </h3>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center text-xs text-gray-300 cursor-pointer p-2 rounded hover:bg-gray-700/50">
                                <input type="checkbox" class="form-checkbox mr-2" id="check-audio">
                                <span>Audio ready</span>
                            </label>
                            <label class="flex items-center text-xs text-gray-300 cursor-pointer p-2 rounded hover:bg-gray-700/50">
                                <input type="checkbox" class="form-checkbox mr-2" id="check-mic">
                                <span>Microphone ready</span>
                            </label>
                            <label class="flex items-center text-xs text-gray-300 cursor-pointer p-2 rounded hover:bg-gray-700/50">
                                <input type="checkbox" class="form-checkbox mr-2" id="check-time">
                                <span>3 hours available</span>
                            </label>
                            <label class="flex items-center text-xs text-gray-300 cursor-pointer p-2 rounded hover:bg-gray-700/50">
                                <input type="checkbox" class="form-checkbox mr-2" id="check-understand">
                                <span>Understood rules</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('student.full-test.index') }}" 
                       class="btn-secondary text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Tests
                    </a>
                    
                    @if($inProgressAttempt)
                        <form action="{{ route('student.full-test.start', $fullTest) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="btn-primary w-full" 
                                    id="continue-test-btn">
                                <i class="fas fa-play mr-2"></i>
                                Continue Test
                            </button>
                        </form>
                    @else
                        <form action="{{ route('student.full-test.start', $fullTest) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="btn-primary w-full" 
                                    id="start-test-btn"
                                    disabled>
                                <i class="fas fa-play mr-2"></i>
                                Start Full Test
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.form-checkbox');
            const startBtn = document.getElementById('start-test-btn');
            
            if (!startBtn) return; // If continue button exists, no need for checks
            
            function checkAllBoxes() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                startBtn.disabled = !allChecked;
                
                // Update button style based on state
                if (allChecked) {
                    startBtn.classList.add('hover:scale-105', 'transition-transform');
                } else {
                    startBtn.classList.remove('hover:scale-105', 'transition-transform');
                }
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', checkAllBoxes);
            });
            
            // Initial check
            checkAllBoxes();
        });
    </script>
    @endpush
</x-student-layout>
