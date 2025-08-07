<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Banned - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <!-- Ban Alert -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">
                            Your Account Has Been {{ Auth::user()->isPermanentlyBanned() ? 'Permanently' : 'Temporarily' }} Banned
                        </h2>
                    </div>
                </div>
                
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong>Reason:</strong> {{ Auth::user()->ban_reason }}
                            </p>
                            @if(Auth::user()->isTemporarilyBanned() && Auth::user()->ban_expires_at)
                                <p class="text-sm text-red-700 mt-1">
                                    <strong>Ban expires on:</strong> {{ Auth::user()->getBanExpiryDate() }}
                                </p>
                            @endif
                            @if(Auth::user()->bannedBy)
                                <p class="text-sm text-red-700 mt-1">
                                    <strong>Banned by:</strong> {{ Auth::user()->bannedBy->name }}
                                </p>
                            @endif
                            <p class="text-sm text-red-700 mt-1">
                                <strong>Date:</strong> {{ Auth::user()->banned_at ? \Carbon\Carbon::parse(Auth::user()->banned_at)->format('F j, Y g:i A') : 'Unknown' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Appeal Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit an Appeal</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    @endif

                    @php
                        $latestAppeal = Auth::user()->latestBanAppeal;
                    @endphp

                    @if($latestAppeal)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Your Latest Appeal</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $latestAppeal->status_badge_color }}">
                                        {{ ucfirst($latestAppeal->status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Submitted:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $latestAppeal->created_at->format('F j, Y g:i A') }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Your appeal:</p>
                                    <p class="text-sm text-gray-900 mt-1">{{ $latestAppeal->appeal_reason }}</p>
                                </div>
                                @if($latestAppeal->admin_response)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-sm text-gray-600">Admin response:</p>
                                        <p class="text-sm text-gray-900 mt-1">{{ $latestAppeal->admin_response }}</p>
                                        @if($latestAppeal->reviewer)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Reviewed by {{ $latestAppeal->reviewer->name }} on {{ $latestAppeal->reviewed_at ? $latestAppeal->reviewed_at->format('F j, Y') : 'Unknown' }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(!$latestAppeal || !$latestAppeal->isPending())
                        <form action="{{ route('banned.appeal') }}" method="POST">
                            @csrf
                            
                            <div class="mb-6">
                                <label for="appeal_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Explain why you believe your ban should be lifted
                                </label>
                                <textarea
                                    name="appeal_reason"
                                    id="appeal_reason"
                                    rows="6"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('appeal_reason') border-red-500 @enderror"
                                    placeholder="Please provide a detailed explanation (minimum 50 characters)..."
                                    required
                                >{{ old('appeal_reason') }}</textarea>
                                @error('appeal_reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Be honest and specific. Explain what happened and why it won't happen again.
                                </p>
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                                    Submit Appeal
                                </button>
                                
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-gray-600">
                                Your appeal is currently being reviewed. Please check back later.
                            </p>
                            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Information Section -->
                <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-blue-800 mb-2">What happens next?</h3>
                    <ul class="list-disc list-inside text-xs text-blue-700 space-y-1">
                        <li>An administrator will review your appeal within 24-48 hours</li>
                        <li>You will be notified via email once a decision is made</li>
                        <li>If approved, you will regain access to your account immediately</li>
                        <li>If rejected, you may submit another appeal after 7 days</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
