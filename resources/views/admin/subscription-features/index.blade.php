<x-admin-layout>
    <x-slot:title>Subscription Features</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Subscription Features</h1>
                <p class="mt-2 text-gray-600">Manage features that can be assigned to subscription plans</p>
            </div>
            <div class="mt-4 flex items-center space-x-3 sm:mt-0">
                <a href="{{ route('admin.subscription-plans.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Plans
                </a>
                <a href="{{ route('admin.subscription-features.create') }}" 
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Feature
                </a>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
        @foreach($features as $feature)
        <div class="overflow-hidden rounded-xl bg-white shadow-sm hover:shadow-md transition-shadow">
            <!-- Feature Header -->
            <div class="border-b border-gray-200 bg-gray-50 p-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        @if($feature->key === 'mock_tests_per_month')
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        @elseif($feature->key === 'ai_writing_evaluation')
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @elseif($feature->key === 'ai_speaking_evaluation')
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </div>
                        @elseif($feature->key === 'detailed_analytics')
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100">
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        @elseif($feature->key === 'priority_support')
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        @else
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $feature->name }}</h3>
                            <code class="mt-1 inline-block rounded bg-gray-200 px-2 py-0.5 text-xs text-gray-600">{{ $feature->key }}</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Body -->
            <div class="p-4">
                @if($feature->description)
                    <p class="mb-4 text-sm text-gray-600">{{ $feature->description }}</p>
                @endif

                <!-- Plans Using This Feature -->
                <div class="mb-4">
                    <h4 class="mb-2 flex items-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Used in Plans
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @if($feature->plans->count() > 0)
                            @foreach($feature->plans as $plan)
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                                    {{ $plan->name }}
                                    @if($plan->pivot->value && $plan->pivot->value !== 'true')
                                        <span class="ml-1 text-indigo-600">({{ $plan->pivot->value }})</span>
                                    @endif
                                </span>
                            @endforeach
                        @else
                            <span class="text-sm italic text-gray-400">Not used in any plan</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.subscription-features.edit', $feature) }}" 
                           class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700 transition-colors">
                            Edit
                        </a>
                        @if($feature->plans->count() == 0)
                        <form action="{{ route('admin.subscription-features.destroy', $feature) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this feature?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                Delete
                            </button>
                        </form>
                        @else
                        <button disabled 
                                class="cursor-not-allowed rounded-lg bg-gray-300 px-3 py-1 text-xs font-medium text-gray-500">
                            Delete
                        </button>
                        @endif
                    </div>
                    <span class="text-xs text-gray-500">ID: {{ $feature->id }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($features->count() == 0)
    <div class="rounded-xl bg-white p-12 text-center shadow-sm">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto mb-6 h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mb-2 text-xl font-semibold text-gray-900">No Features Yet</h3>
            <p class="mb-6 text-gray-600">Get started by creating your first subscription feature.</p>
            <a href="{{ route('admin.subscription-features.create') }}" 
               class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create First Feature
            </a>
        </div>
    </div>
    @endif

    <!-- Feature Statistics -->
    @if($features->count() > 0)
    <div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
        <h3 class="mb-6 text-lg font-semibold text-gray-900">Feature Statistics</h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Features</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $features->count() }}</p>
                    </div>
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Features</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $features->filter(function($f) { return $f->plans->count() > 0; })->count() }}</p>
                    </div>
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Unused Features</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $features->filter(function($f) { return $f->plans->count() == 0; })->count() }}</p>
                    </div>
                    <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Avg. per Plan</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">
                            {{ \App\Models\SubscriptionPlan::count() > 0 ? round($features->sum(function($f) { return $f->plans->count(); }) / \App\Models\SubscriptionPlan::count(), 1) : 0 }}
                        </p>
                    </div>
                    <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-admin-layout>