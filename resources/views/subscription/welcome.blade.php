<x-student-layout>
    <x-slot:title>Welcome to Premium!</x-slot>
    
    <!-- Celebration Animation Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <!-- Confetti-like particles -->
        @for($i = 0; $i < 10; $i++)
        <div class="particle absolute w-2 h-2 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full opacity-0 animate-confetti"
             style="left: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.1 }}s;"></div>
        @endfor
    </div>

    <section class="relative px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-4xl mx-auto">
            <!-- Success Animation -->
            <div class="text-center mb-12">
                <div class="relative inline-block mb-8">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/30 animate-bounce">
                        <i class="fas fa-check text-5xl text-white"></i>
                    </div>
                    <div class="absolute inset-0 w-32 h-32 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 animate-ping"></div>
                </div>
                
                <!-- Welcome Message -->
                <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    Welcome to <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Premium!</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Thank you for subscribing to our <span class="text-purple-400 font-semibold">{{ auth()->user()->activeSubscription()->plan->name }}</span> plan.
                    You now have access to all premium features!
                </p>
            </div>

            <!-- What's Next Section -->
            <div class="glass rounded-2xl p-8 lg:p-10 mb-8 border border-purple-500/30 bg-gradient-to-br from-purple-900/20 to-pink-900/20">
                <h2 class="text-2xl font-bold text-white mb-8 flex items-center justify-center">
                    <i class="fas fa-rocket text-purple-400 mr-3"></i>
                    What's Next?
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Take a Mock Test -->
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all group">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-pencil-alt text-2xl text-blue-400"></i>
                        </div>
                        <h3 class="font-semibold text-white text-lg mb-2">Take a Mock Test</h3>
                        <p class="text-gray-400 text-sm mb-4">Start with a full IELTS mock test to assess your current level.</p>
                        <a href="{{ route('student.index') }}" 
                           class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium group">
                            Start Test 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    
                    <!-- Try AI Evaluation -->
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all group">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-robot text-2xl text-purple-400"></i>
                        </div>
                        <h3 class="font-semibold text-white text-lg mb-2">Try AI Evaluation</h3>
                        <p class="text-gray-400 text-sm mb-4">Get instant feedback on your writing and speaking with our AI evaluator.</p>
                        <a href="#" 
                           class="inline-flex items-center text-purple-400 hover:text-purple-300 font-medium group">
                            Learn More 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    
                    <!-- Track Your Progress -->
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all group">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-line text-2xl text-green-400"></i>
                        </div>
                        <h3 class="font-semibold text-white text-lg mb-2">Track Your Progress</h3>
                        <p class="text-gray-400 text-sm mb-4">Monitor your improvement with detailed analytics and insights.</p>
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center text-green-400 hover:text-green-300 font-medium group">
                            View Dashboard 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Premium Features Reminder -->
            <div class="glass rounded-2xl p-6 mb-8 border border-white/10">
                <h3 class="text-lg font-semibold text-white mb-4 text-center">Your Premium Benefits</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-infinity text-purple-400"></i>
                        </div>
                        <p class="text-sm text-gray-300">Unlimited Tests</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-brain text-blue-400"></i>
                        </div>
                        <p class="text-sm text-gray-300">AI Evaluation</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-analytics text-green-400"></i>
                        </div>
                        <p class="text-sm text-gray-300">Advanced Analytics</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-headset text-amber-400"></i>
                        </div>
                        <p class="text-sm text-gray-300">Priority Support</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="{{ route('student.dashboard') }}" 
                   class="px-8 py-4 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all shadow-lg hover:shadow-xl neon-purple text-center">
                    <i class="fas fa-home mr-2"></i>Go to Dashboard
                </a>
                <a href="{{ route('subscription.index') }}" 
                   class="px-8 py-4 rounded-xl font-semibold glass text-white hover:border-purple-500/50 transition-all border border-white/20 text-center">
                    <i class="fas fa-cog mr-2"></i>Manage Subscription
                </a>
            </div>

            <!-- Support Section -->
            <div class="text-center">
                <div class="glass rounded-xl p-6 inline-block border border-white/10">
                    <p class="text-gray-400 mb-2">Need help getting started?</p>
                    <p class="text-white">
                        Contact our support team at 
                        <a href="mailto:support@cdielts.com" class="text-purple-400 hover:text-purple-300 underline">
                            support@cdielts.com
                        </a>
                    </p>
                    <div class="flex items-center justify-center space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes confetti {
            0% {
                opacity: 0;
                transform: translateY(-100vh) rotate(0deg);
            }
            10% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(100vh) rotate(720deg);
            }
        }
        .animate-confetti {
            animation: confetti 3s ease-out forwards;
        }
    </style>
</x-student-layout>