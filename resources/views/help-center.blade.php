<x-guest-layout>
    <x-slot name="title">Help Center - Find Answers</x-slot>
    
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-white to-gray-50 py-16 md:py-20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23dc2626" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Icon -->
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
                    How Can We
                    <span class="relative inline-block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-600">Help?</span>
                        <svg class="absolute -bottom-2 left-0 w-full" height="10" viewBox="0 0 200 10">
                            <path d="M0,8 Q50,0 100,8 T200,8" stroke="#ef4444" stroke-width="3" fill="none" opacity="0.6"/>
                        </svg>
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-700 mb-8 font-medium">
                    Search our knowledge base or browse categories to find the answers you need
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" id="helpSearch"
                            class="w-full px-6 py-4 pr-12 rounded-2xl border-2 border-gray-200 focus:border-red-500 focus:outline-none text-lg shadow-lg"
                            placeholder="Search for help...">
                        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,96L48,80C96,64,192,32,288,26.7C384,21,480,43,576,48C672,53,768,43,864,42.7C960,43,1056,53,1152,58.7C1248,64,1344,64,1392,64L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    <!-- Popular Topics -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Popular Topics</h2>
                    <p class="text-lg text-gray-600">Browse our most visited help articles</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Topic 1 -->
                    <a href="#getting-started" class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Getting Started</h3>
                                <p class="text-sm text-gray-600">Learn the basics of using our platform</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Topic 2 -->
                    <a href="#taking-tests" class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Taking Tests</h3>
                                <p class="text-sm text-gray-600">How to start and complete mock tests</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Topic 3 -->
                    <a href="#scoring" class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Scoring & Evaluation</h3>
                                <p class="text-sm text-gray-600">Understanding your test results</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Topic 4 -->
                    <a href="#ai-evaluation" class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">AI Evaluation</h3>
                                <p class="text-sm text-gray-600">How our AI scoring works</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Topic 5 -->
                    <a href="#account" class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Account Management</h3>
                                <p class="text-sm text-gray-600">Profile, settings, and preferences</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Topic 6 -->
                    <a href="#troubleshooting" class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Troubleshooting</h3>
                                <p class="text-sm text-gray-600">Common issues and solutions</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Categories -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <!-- Getting Started -->
                <div id="getting-started" class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Getting Started</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                How do I create an account?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Click the "Start Now" or "Register" button on the homepage. Fill in your name, email, and password. Verify your email address through the link sent to your inbox, and you're ready to start practicing!
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Is the platform really free?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Yes! All our mock tests, AI evaluations, and basic features are completely free. We offer premium features like human evaluation and personalized coaching for a small fee, but the core platform is 100% free to use.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                What tests are available?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                We offer all four IELTS modules: Listening, Reading, Writing, and Speaking. Each module contains 100+ practice tests following the official IELTS format for both Academic and General Training versions.
                            </p>
                        </details>
                    </div>
                </div>

                <!-- Taking Tests -->
                <div id="taking-tests" class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Taking Tests</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                How do I start a test?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Log in to your dashboard, select the test module (Listening, Reading, Writing, or Speaking), choose a test from the available options, and click "Start Test". Follow the on-screen instructions to complete the test within the time limit.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Can I pause a test and continue later?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                To maintain real exam conditions, tests must be completed in one session. However, you can retake any test as many times as you want to practice and improve your score.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                What equipment do I need for Speaking tests?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                You'll need a working microphone to record your responses. Most laptops and smartphones have built-in microphones. Ensure you're in a quiet environment and grant browser permission to access your microphone.
                            </p>
                        </details>
                    </div>
                </div>

                <!-- Scoring & Evaluation -->
                <div id="scoring" class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Scoring & Evaluation</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                How is my score calculated?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Listening and Reading are scored automatically based on correct answers. Writing and Speaking use our AI evaluation system that analyzes grammar, vocabulary, coherence, fluency, and task achievement according to official IELTS criteria.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                How accurate is the AI evaluation?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Our AI system has been trained on thousands of real IELTS responses and typically provides scores within 0.5 band of human evaluators. For the most accurate assessment, consider our premium human evaluation service.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Can I see detailed feedback?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Yes! After completing a test, you'll receive detailed feedback including correct answers, explanations, your strengths and weaknesses, and specific recommendations for improvement.
                            </p>
                        </details>
                    </div>
                </div>

                <!-- AI Evaluation -->
                <div id="ai-evaluation" class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">AI Evaluation</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                What does AI evaluation check?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Our AI evaluates: Task Achievement (addressing the question), Coherence & Cohesion (organization and flow), Lexical Resource (vocabulary range and accuracy), and Grammatical Range & Accuracy (sentence structures and errors).
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                How quickly do I get AI feedback?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                AI evaluation is instant! As soon as you submit your Writing or Speaking test, you'll receive your band score and detailed feedback within seconds.
                            </p>
                        </details>
                    </div>
                </div>

                <!-- Account Management -->
                <div id="account" class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Account Management</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                How do I reset my password?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Click "Forgot Password?" on the login page, enter your email address, and follow the instructions sent to your inbox to create a new password.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Can I delete my account?
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Yes. Go to Settings > Account > Delete Account. Note that this action is permanent and will remove all your test history and progress.
                            </p>
                        </details>
                    </div>
                </div>

                <!-- Troubleshooting -->
                <div id="troubleshooting" class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Troubleshooting</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                The audio isn't playing in Listening tests
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Check your device's volume settings and ensure your browser has permission to play audio. Try refreshing the page or using a different browser (Chrome or Firefox recommended).
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                My microphone isn't working for Speaking tests
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Ensure you've granted browser permission to access your microphone. Check your system settings to verify the correct microphone is selected. Try using headphones with a built-in microphone.
                            </p>
                        </details>
                        
                        <details class="group bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                The page is loading slowly
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Check your internet connection speed. Clear your browser cache and cookies. If the problem persists, try accessing the platform during off-peak hours or contact our support team.
                            </p>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Still Need Help -->
    <section class="py-16 bg-gradient-to-br from-red-500 to-red-600">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center text-white">
                <svg class="w-16 h-16 mx-auto mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Still Need Help?</h2>
                <p class="text-xl mb-8 text-red-50">Our support team is ready to assist you</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact') }}" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-white text-red-600 font-bold rounded-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Support
                    </a>
                    
                    <button onclick="startChat()" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-white/20 backdrop-blur text-white font-bold rounded-xl hover:bg-white/30 transition-all border-2 border-white/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Start Live Chat
                    </button>
                </div>
            </div>
        </div>
    </section>

    <x-slot name="scripts">
        <script>
            // Search functionality
            document.getElementById('helpSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const allDetails = document.querySelectorAll('details');
                
                allDetails.forEach(detail => {
                    const content = detail.textContent.toLowerCase();
                    const parent = detail.closest('div[id]');
                    
                    if (content.includes(searchTerm) || searchTerm === '') {
                        detail.style.display = 'block';
                        if (parent) parent.style.display = 'block';
                    } else {
                        detail.style.display = 'none';
                    }
                });
                
                // Hide category sections with no visible details
                document.querySelectorAll('section > div > div > div[id]').forEach(section => {
                    const visibleDetails = section.querySelectorAll('details[style="display: block"]');
                    section.style.display = visibleDetails.length > 0 || searchTerm === '' ? 'block' : 'none';
                });
            });
            
            // Start Chat Function
            function startChat() {
                alert('Live chat feature coming soon! For immediate assistance, please email us at support@ieltsmock.com');
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        </script>
    </x-slot>
</x-guest-layout>
