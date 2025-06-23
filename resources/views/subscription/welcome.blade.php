@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-4xl mx-auto text-center">
        {{-- Success Icon --}}
        <div class="mb-8">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
        </div>
        
        {{-- Welcome Message --}}
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Premium!</h1>
        <p class="text-xl text-gray-600 mb-8">
            Thank you for subscribing to our {{ auth()->user()->activeSubscription()->plan->name }} plan.
            You now have access to all premium features.
        </p>
        
        {{-- What's Next --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8 text-left">
            <h2 class="text-2xl font-semibold mb-6">What's Next?</h2>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="bg-blue-100 rounded-full p-2 mr-4">
                        <i class="fas fa-pencil-alt text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Take a Mock Test</h3>
                        <p class="text-gray-600">Start with a full IELTS mock test to assess your current level.</p>
                        <a href="{{ route('student.index') }}" class="text-blue-600 hover:underline mt-1 inline-block">
                            Start Test →
                        </a>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-purple-100 rounded-full p-2 mr-4">
                        <i class="fas fa-robot text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Try AI Evaluation</h3>
                        <p class="text-gray-600">Get instant feedback on your writing and speaking with our AI evaluator.</p>
                        <a href="#" class="text-purple-600 hover:underline mt-1 inline-block">
                            Learn More →
                        </a>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-green-100 rounded-full p-2 mr-4">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Track Your Progress</h3>
                        <p class="text-gray-600">Monitor your improvement with detailed analytics and insights.</p>
                        <a href="{{ route('student.dashboard') }}" class="text-green-600 hover:underline mt-1 inline-block">
                            View Dashboard →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Support --}}
        <div class="text-gray-600">
            <p>Need help? Contact our support team at <a href="mailto:support@rocks.com" class="text-blue-600 hover:underline">support@rocks.com</a></p>
        </div>
    </div>
</div>
@endsection