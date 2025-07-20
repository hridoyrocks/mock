<x-admin-layout>
    <x-slot:title>Token Packages</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Token Packages</h2>
                    <p class="mt-1 text-sm text-gray-600">Manage token pricing packages for human evaluation</p>
                </div>
                <a href="{{ route('admin.token-packages.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Package
                </a>
            </div>
        </div>

        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($packages as $package)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ !$package->is_active ? 'opacity-60' : '' }}">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $package->name }}</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-gray-900">{{ $package->tokens_count }}</span>
                            <span class="ml-2 text-gray-500">tokens</span>
                        </div>
                        
                        @if($package->bonus_tokens > 0)
                        <div class="flex items-center text-green-600">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-sm font-medium">{{ $package->bonus_tokens }} bonus tokens</span>
                        </div>
                        @endif
                        
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total tokens:</span>
                                <span class="font-medium text-gray-900">{{ $package->total_tokens }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-500">Price per token:</span>
                                <span class="font-medium text-gray-900">${{ number_format($package->price_per_token, 3) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <span class="text-3xl font-bold text-gray-900">${{ number_format($package->price, 2) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.token-packages.edit', $package) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                            
                            <form action="{{ route('admin.token-packages.toggle-status', $package) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-sm">
                                    {{ $package->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.token-packages.destroy', $package) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this package?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No packages</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new token package.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.token-packages.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Package
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
        <div class="bg-white px-4 py-3 rounded-lg shadow-sm">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
