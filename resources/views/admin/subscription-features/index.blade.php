<x-admin-layout>
    <x-slot:title>Subscription Features</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Subscription Features
                </h1>
                <p class="text-gray-600 mt-2">Manage features that can be assigned to subscription plans</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.subscription-plans.index') }}" 
                   class="group relative inline-flex items-center px-5 py-2.5 bg-white border-2 border-indigo-200 rounded-xl hover:border-indigo-400 transition-all duration-300 shadow-sm hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-semibold text-gray-700">Back to Plans</span>
                </a>
                <a href="{{ route('admin.subscription-features.create') }}" 
                   class="group relative inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="font-semibold">Create Feature</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        {{-- Features Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($features as $feature)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group transform hover:-translate-y-1">
                {{-- Feature Header --}}
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 bg-white/10 rounded-full p-8 transform rotate-12"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                @if($feature->icon)
                                    <div class="bg-white/20 p-3 rounded-lg mr-4">
                                        <i class="{{ $feature->icon }} text-2xl text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $feature->name }}</h3>
                                    <code class="text-indigo-100 text-sm bg-white/10 px-2 py-1 rounded mt-1 inline-block">{{ $feature->key }}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Feature Body --}}
                <div class="p-6">
                    @if($feature->description)
                        <p class="text-gray-600 mb-4">{{ $feature->description }}</p>
                    @endif

                    {{-- Plans Using This Feature --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Used in Plans
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @if($feature->plans->count() > 0)
                                @foreach($feature->plans as $plan)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 border border-indigo-200">
                                        {{ $plan->name }}
                                        @if($plan->pivot->value && $plan->pivot->value !== 'true')
                                            <span class="ml-1 text-purple-600">({{ $plan->pivot->value }})</span>
                                        @endif
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400 italic text-sm">Not used in any plan</span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.subscription-features.edit', $feature) }}" 
                               class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @if($feature->plans->count() == 0)
                            <form action="{{ route('admin.subscription-features.destroy', $feature) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this feature?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors group">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @else
                            <button disabled class="p-2 text-gray-300 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">
                            ID: {{ $feature->id }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        @if($features->count() == 0)
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Features Yet</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first subscription feature.</p>
                <a href="{{ route('admin.subscription-features.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create First Feature
                </a>
            </div>
        </div>
        @endif

        {{-- Feature Statistics --}}
        @if($features->count() > 0)
        <div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
            <h3 class="text-2xl font-bold mb-6">Feature Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm">Total Features</p>
                            <p class="text-3xl font-bold mt-1">{{ $features->count() }}</p>
                        </div>
                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm">Active Features</p>
                            <p class="text-3xl font-bold mt-1">{{ $features->filter(function($f) { return $f->plans->count() > 0; })->count() }}</p>
                        </div>
                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm">Unused Features</p>
                            <p class="text-3xl font-bold mt-1">{{ $features->filter(function($f) { return $f->plans->count() == 0; })->count() }}</p>
                        </div>
                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm">Avg. per Plan</p>
                            <p class="text-3xl font-bold mt-1">
                                {{ \App\Models\SubscriptionPlan::count() > 0 ? round($features->sum(function($f) { return $f->plans->count(); }) / \App\Models\SubscriptionPlan::count(), 1) : 0 }}
                            </p>
                        </div>
                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>