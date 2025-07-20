<x-student-layout>
    <x-slot:title>Select Teacher for Evaluation</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
               

                <!-- Header Content -->
                <div class="glass rounded-2xl p-8">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-3">
                                <i class="fas fa-user-tie text-purple-400 mr-3"></i>
                                Choose Your IELTS Expert
                            </h1>
                            <p class="text-gray-300 text-lg">
                                Get professional evaluation for your {{ ucfirst($section) }} test
                            </p>
                            <div class="flex flex-wrap items-center gap-4 mt-4">
                                <span class="inline-flex items-center glass px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-file-alt text-purple-400 mr-2"></i>
                                    <span class="text-gray-300">Test:</span>
                                    <span class="text-white ml-1 font-medium">{{ $attempt->testSet->title }}</span>
                                </span>
                                <span class="inline-flex items-center glass px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-calendar text-blue-400 mr-2"></i>
                                    <span class="text-gray-300">Taken:</span>
                                    <span class="text-white ml-1 font-medium">{{ $attempt->created_at->format('M d, Y') }}</span>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Token Balance Card -->
                        <div class="glass rounded-xl p-6 border-yellow-500/30 hover:border-yellow-500/50 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-500 flex items-center justify-center neon-yellow">
                                    <i class="fas fa-coins text-2xl text-white"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Your Balance</p>
                                    <p class="text-2xl font-bold text-white">{{ $tokenBalance->available_tokens }} Tokens</p>
                                    <a href="{{ route('student.tokens.purchase') }}" 
                                       class="text-yellow-400 hover:text-yellow-300 text-xs transition-colors">
                                        <i class="fas fa-plus-circle mr-1"></i>Buy More
                                    </a>
                                </div>
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
            @if($teachers->isEmpty())
                <!-- No Teachers Available -->
                <div class="glass rounded-2xl p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-user-slash text-6xl text-gray-600 mb-6"></i>
                        <h3 class="text-2xl font-bold text-white mb-3">No Teachers Available</h3>
                        <p class="text-gray-400 mb-8">
                            We're sorry, but there are no teachers available for {{ $section }} evaluation at the moment. 
                            Please check back later or contact support.
                        </p>
                        <a href="{{ route('student.results.show', $attempt) }}" 
                           class="inline-flex items-center px-6 py-3 rounded-xl glass text-white hover:border-purple-500/50 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Results
                        </a>
                    </div>
                </div>
            @else
                <!-- Filter Options -->
                <div class="glass rounded-xl p-4 mb-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 text-sm">Sort by:</span>
                            <button onclick="sortTeachers('rating')" 
                                    class="px-4 py-2 rounded-lg glass text-white text-sm hover:border-purple-500/50 transition-all">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>Rating
                            </button>
                            <button onclick="sortTeachers('price')" 
                                    class="px-4 py-2 rounded-lg glass text-white text-sm hover:border-purple-500/50 transition-all">
                                <i class="fas fa-coins text-yellow-400 mr-2"></i>Price
                            </button>
                            <button onclick="sortTeachers('experience')" 
                                    class="px-4 py-2 rounded-lg glass text-white text-sm hover:border-purple-500/50 transition-all">
                                <i class="fas fa-briefcase text-blue-400 mr-2"></i>Experience
                            </button>
                        </div>
                        <div class="text-gray-400 text-sm">
                            <i class="fas fa-users mr-2"></i>
                            <span class="text-white font-medium">{{ $teachers->count() }}</span> teachers available
                        </div>
                    </div>
                </div>

                <!-- Teachers Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($teachers as $teacher)
                    <div class="glass rounded-2xl overflow-hidden hover:border-purple-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Teacher Header -->
                        <div class="relative p-6 pb-4">
                            @if($teacher->rating >= 4.8)
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-gradient-to-r from-yellow-500 to-amber-500 text-white text-xs rounded-full font-semibold">
                                        <i class="fas fa-crown mr-1"></i>Top Rated
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                @if($teacher->user->avatar_url)
                                    <img src="{{ $teacher->user->avatar_url }}" 
                                         alt="{{ $teacher->user->name }}" 
                                         class="w-20 h-20 rounded-2xl object-cover border-2 border-purple-500/30">
                                @else
                                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-2xl">
                                        {{ substr($teacher->user->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <!-- Basic Info -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white mb-1">{{ $teacher->user->name }}</h3>
                                    
                                    <!-- Rating -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $teacher->rating ? 'text-yellow-400' : 'text-gray-600' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="text-white font-medium">{{ number_format($teacher->rating, 1) }}</span>
                                        <span class="text-gray-400 text-sm">({{ $teacher->total_evaluations_done }})</span>
                                    </div>
                                    
                                    <!-- Quick Stats -->
                                    <div class="flex items-center gap-4 text-xs">
                                        <span class="text-gray-400">
                                            <i class="fas fa-briefcase text-blue-400 mr-1"></i>
                                            {{ $teacher->experience_years }}y exp
                                        </span>
                                        <span class="text-gray-400">
                                            <i class="fas fa-clock text-green-400 mr-1"></i>
                                            ~{{ $teacher->average_turnaround_hours }}h
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Specializations -->
                        @if($teacher->specialization && count($teacher->specialization) > 0)
                        <div class="px-6 pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($teacher->specialization as $spec)
                                    <span class="px-3 py-1 text-xs rounded-full glass border-blue-500/30 text-blue-400">
                                        {{ ucfirst($spec) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- About Section -->
                        @if($teacher->bio)
                        <div class="px-6 pb-4">
                            <p class="text-gray-400 text-sm line-clamp-2">{{ $teacher->bio }}</p>
                        </div>
                        @endif

                        <!-- Languages -->
                        @if($teacher->languages && count($teacher->languages) > 0)
                        <div class="px-6 pb-4">
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-language text-purple-400"></i>
                                <span class="text-gray-400">Languages:</span>
                                <span class="text-white">{{ implode(', ', $teacher->languages) }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Pricing Section -->
                        <div class="bg-gradient-to-br from-purple-600/10 to-pink-600/10 p-6 border-t border-white/10">
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <!-- Normal Evaluation -->
                                <div class="glass rounded-xl p-4 text-center hover:border-purple-500/50 transition-all cursor-pointer" 
                                     onclick="selectPricing({{ $teacher->id }}, 'normal')">
                                    <p class="text-xs text-gray-400 mb-1">Normal (48h)</p>
                                    <p class="text-2xl font-bold text-white">
                                        {{ $teacher->token_price }}
                                    </p>
                                    <p class="text-xs text-purple-400">tokens</p>
                                </div>
                                
                                <!-- Urgent Evaluation -->
                                <div class="glass rounded-xl p-4 text-center border-orange-500/30 hover:border-orange-500/50 transition-all cursor-pointer" 
                                     onclick="selectPricing({{ $teacher->id }}, 'urgent')">
                                    <div class="flex items-center justify-center gap-1 mb-1">
                                        <i class="fas fa-fire text-orange-400 text-xs"></i>
                                        <p class="text-xs text-gray-400">Urgent (12h)</p>
                                    </div>
                                    <p class="text-2xl font-bold text-white">
                                        {{ $teacher->urgent_price }}
                                    </p>
                                    <p class="text-xs text-orange-400">tokens</p>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            @if($tokenBalance->available_tokens >= $teacher->token_price)
                                <button onclick="selectTeacher({{ $teacher->id }}, '{{ $teacher->user->name }}', {{ $teacher->token_price }}, {{ $teacher->urgent_price }})" 
                                        class="w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Select Teacher
                                </button>
                            @else
                                <div class="text-center">
                                    <p class="text-red-400 text-sm mb-2">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Need {{ $teacher->token_price }} tokens
                                    </p>
                                    <a href="{{ route('student.tokens.purchase') }}" 
                                       class="block w-full py-3 rounded-xl glass text-white hover:border-purple-500/50 transition-all text-center">
                                        <i class="fas fa-coins mr-2"></i>
                                        Buy Tokens
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Info Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="glass rounded-xl p-6 text-center hover:border-purple-500/30 transition-all">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-check text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Certified Experts</h3>
                        <p class="text-gray-400 text-sm">All our teachers are certified IELTS examiners with proven track records</p>
                    </div>
                    
                    <div class="glass rounded-xl p-6 text-center hover:border-purple-500/30 transition-all">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-comments text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Detailed Feedback</h3>
                        <p class="text-gray-400 text-sm">Get comprehensive feedback on all IELTS criteria with improvement tips</p>
                    </div>
                    
                    <div class="glass rounded-xl p-6 text-center hover:border-purple-500/30 transition-all">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Quality Guaranteed</h3>
                        <p class="text-gray-400 text-sm">100% satisfaction guarantee with our professional evaluation service</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
    
    <!-- Teacher Selection Modal -->
    <div id="teacherModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closeTeacherModal()" class="fixed inset-0 bg-black bg-opacity-80 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative glass rounded-2xl w-full max-w-md p-8 transform transition-all">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-white mb-2">Confirm Evaluation Request</h3>
                    <p class="text-gray-400">Teacher: <span id="selectedTeacherName" class="text-white font-medium"></span></p>
                </div>
                
                <form action="{{ route('student.evaluation.request', $attempt) }}" method="POST">
                    @csrf
                    <input type="hidden" name="teacher_id" id="selectedTeacherId">
                    
                    <!-- Priority Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Select Priority</label>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 rounded-xl glass cursor-pointer hover:border-purple-500/50 transition-all">
                                <input type="radio" name="priority" value="normal" checked class="sr-only" onchange="updatePrice('normal')">
                                <div class="flex items-center justify-between w-full">
                                    <div>
                                        <p class="font-medium text-white">Normal Evaluation</p>
                                        <p class="text-xs text-gray-400">Delivered within 48 hours</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-white">
                                            <i class="fas fa-coins text-yellow-400 mr-1"></i>
                                            <span id="normalPrice">0</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-3 priority-check">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 rounded-xl glass cursor-pointer hover:border-orange-500/50 transition-all">
                                <input type="radio" name="priority" value="urgent" class="sr-only" onchange="updatePrice('urgent')">
                                <div class="flex items-center justify-between w-full">
                                    <div>
                                        <p class="font-medium text-white">
                                            <i class="fas fa-fire text-orange-400 mr-1"></i>
                                            Urgent Evaluation
                                        </p>
                                        <p class="text-xs text-gray-400">Delivered within 12 hours</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-white">
                                            <i class="fas fa-coins text-yellow-400 mr-1"></i>
                                            <span id="urgentPrice">0</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-3 priority-check hidden">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Token Summary -->
                    <div class="glass rounded-xl p-4 mb-6 bg-gradient-to-br from-purple-600/10 to-pink-600/10">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Tokens Required</span>
                            <span class="text-xl font-bold text-white" id="totalTokens">0</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-400">Your Balance</span>
                            <span class="text-white">{{ $tokenBalance->available_tokens }}</span>
                        </div>
                        <hr class="my-2 border-white/10">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Remaining After</span>
                            <span class="text-white font-medium" id="remainingTokens">0</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button" onclick="closeTeacherModal()" 
                                class="flex-1 py-3 rounded-xl glass text-white hover:border-gray-500/50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 py-3 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all transform hover:scale-105">
                            <i class="fas fa-check mr-2"></i>
                            Confirm Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        let selectedNormalPrice = 0;
        let selectedUrgentPrice = 0;
        let userBalance = {{ $tokenBalance->available_tokens }};
        
        function selectTeacher(teacherId, teacherName, normalPrice, urgentPrice) {
            document.getElementById('selectedTeacherId').value = teacherId;
            document.getElementById('selectedTeacherName').textContent = teacherName;
            document.getElementById('normalPrice').textContent = normalPrice;
            document.getElementById('urgentPrice').textContent = urgentPrice;
            
            selectedNormalPrice = normalPrice;
            selectedUrgentPrice = urgentPrice;
            
            // Reset to normal priority
            document.querySelector('input[value="normal"]').checked = true;
            updatePrice('normal');
            
            // Show modal
            document.getElementById('teacherModal').classList.remove('hidden');
        }
        
        function closeTeacherModal() {
            document.getElementById('teacherModal').classList.add('hidden');
        }
        
        function updatePrice(priority) {
            const price = priority === 'urgent' ? selectedUrgentPrice : selectedNormalPrice;
            document.getElementById('totalTokens').textContent = price;
            document.getElementById('remainingTokens').textContent = userBalance - price;
            
            // Update check marks
            document.querySelectorAll('.priority-check').forEach(el => el.classList.add('hidden'));
            document.querySelector(`input[value="${priority}"]`).parentElement.querySelector('.priority-check').classList.remove('hidden');
            
            // Update remaining tokens color
            const remaining = userBalance - price;
            const remainingEl = document.getElementById('remainingTokens');
            if (remaining < 0) {
                remainingEl.classList.add('text-red-400');
                remainingEl.classList.remove('text-white');
            } else {
                remainingEl.classList.remove('text-red-400');
                remainingEl.classList.add('text-white');
            }
        }
        
        function sortTeachers(sortBy) {
            // This would be implemented with AJAX or page reload with sorting parameter
            console.log('Sorting by:', sortBy);
        }
        
        function selectPricing(teacherId, priority) {
            // Visual feedback for pricing selection
            console.log('Selected pricing:', teacherId, priority);
        }
        
        // Click outside to close modal
        window.onclick = function(event) {
            const modal = document.getElementById('teacherModal');
            if (event.target == modal) {
                closeTeacherModal();
            }
        }
    </script>
    @endpush
    
    @push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .neon-yellow {
            box-shadow: 0 0 20px rgba(250, 204, 21, 0.5),
                        0 0 40px rgba(250, 204, 21, 0.3),
                        0 0 60px rgba(250, 204, 21, 0.1);
        }
    </style>
    @endpush
</x-student-layout>
