<x-admin-layout>
    <x-slot name="title">Review Ban Appeal</x-slot>
    
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Review Ban Appeal</h1>
            
            <!-- User Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $banAppeal->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $banAppeal->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ban Type</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $banAppeal->user->isPermanentlyBanned() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($banAppeal->user->ban_type) }}
                                </span>
                            </dd>
                        </div>
                        @if($banAppeal->user->isTemporarilyBanned())
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expires</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $banAppeal->user->getBanExpiryDate() }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            <!-- Ban Details -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ban Details</h2>
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ban Reason</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ $banAppeal->user->ban_reason }}</p>
                            </div>
                            <p class="mt-2 text-xs text-red-600">
                            Banned on {{ $banAppeal->user->banned_at ? \Carbon\Carbon::parse($banAppeal->user->banned_at)->format('F j, Y g:i A') : 'Unknown' }}
                            @if($banAppeal->user->bannedBy)
                            by {{ $banAppeal->user->bannedBy->name }}
                            @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Appeal Details -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Appeal Details</h2>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">{{ $banAppeal->appeal_reason }}</p>
                    <p class="mt-2 text-xs text-gray-600">
                        Submitted on {{ $banAppeal->created_at->format('F j, Y g:i A') }}
                    </p>
                </div>
            </div>
            
            <!-- Previous Appeals -->
            @php
                $previousAppeals = $banAppeal->user->banAppeals()
                    ->where('id', '!=', $banAppeal->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp
            
            @if($previousAppeals->count() > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Previous Appeals</h2>
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewed By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($previousAppeals as $previousAppeal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $previousAppeal->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $previousAppeal->status_badge_color }}">
                                                {{ ucfirst($previousAppeal->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $previousAppeal->reviewer?->name ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            <!-- Review Actions -->
            @if($banAppeal->isPending())
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Decision</h2>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Approve Form -->
                        <div>
                            <form action="{{ route('admin.ban-appeals.approve', $banAppeal) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="approve_response" class="block text-sm font-medium text-gray-700">Response (for approval)</label>
                                    <textarea name="admin_response" 
                                              id="approve_response" 
                                              rows="4" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                              placeholder="Explain why the appeal is approved..."
                                              required></textarea>
                                </div>
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Approve & Unban User
                                </button>
                            </form>
                        </div>
                        
                        <!-- Reject Form -->
                        <div>
                            <form action="{{ route('admin.ban-appeals.reject', $banAppeal) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="reject_response" class="block text-sm font-medium text-gray-700">Response (for rejection)</label>
                                    <textarea name="admin_response" 
                                              id="reject_response" 
                                              rows="4" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                              placeholder="Explain why the appeal is rejected..."
                                              required></textarea>
                                </div>
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject Appeal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Already Reviewed -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Review Decision</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><strong>Status:</strong> 
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $banAppeal->status_badge_color }}">
                                        {{ ucfirst($banAppeal->status) }}
                                    </span>
                                </p>
                                <p class="mt-2"><strong>Admin Response:</strong></p>
                                <p class="mt-1">{{ $banAppeal->admin_response }}</p>
                                @if($banAppeal->reviewer)
                                    <p class="mt-2 text-xs">
                                        Reviewed by {{ $banAppeal->reviewer->name }} 
                                        on {{ $banAppeal->reviewed_at ? $banAppeal->reviewed_at->format('F j, Y g:i A') : 'Unknown' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.ban-appeals.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Appeals
        </a>
    </div>
</div>
</x-admin-layout>
