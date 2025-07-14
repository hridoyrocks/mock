{{-- resources/views/admin/maintenance/index.blade.php --}}
<x-admin-layout>
    <x-slot:title>Maintenance Mode Management</x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Maintenance Mode</h1>
            <p class="mt-2 text-gray-600">Control platform maintenance mode for student users</p>
        </div>

        <!-- Current Status Card -->
        <div class="mb-8">
            <div class="rounded-xl bg-white shadow-sm p-6 border-2 {{ $currentMaintenance && $currentMaintenance->is_active ? 'border-red-500' : 'border-green-500' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Current Status</h2>
                        <div class="flex items-center space-x-3">
                            @if($currentMaintenance && $currentMaintenance->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 mr-2 bg-red-500 rounded-full animate-pulse"></span>
                                    Maintenance Active
                                </span>
                                <span class="text-sm text-gray-600">
                                    Since: {{ $currentMaintenance->started_at->format('M d, Y h:i A') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                                    System Operational
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        @if($currentMaintenance && $currentMaintenance->is_active)
                            <form action="{{ route('admin.maintenance.toggle') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="disable">
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to disable maintenance mode?')"
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-power-off mr-2"></i>
                                    End Maintenance
                                </button>
                            </form>
                        @else
                            <button onclick="openMaintenanceModal()" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-tools mr-2"></i>
                                Enable Maintenance
                            </button>
                        @endif
                    </div>
                </div>
                
                @if($currentMaintenance && $currentMaintenance->is_active)
                    <div class="mt-6 border-t pt-6">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $currentMaintenance->title }}</h3>
                        <p class="text-gray-600">{{ $currentMaintenance->message }}</p>
                        @if($currentMaintenance->expected_end_at)
                            <p class="mt-2 text-sm text-gray-500">
                                Expected End: {{ $currentMaintenance->expected_end_at->format('M d, Y h:i A') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Maintenance History -->
        <div class="rounded-xl bg-white shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Maintenance History</h2>
            
            @if($maintenanceHistory && $maintenanceHistory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Started At
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Duration
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($maintenanceHistory as $history)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $history->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($history->message, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($history->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Completed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $history->started_at ? $history->started_at->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($history->started_at)
                                            @if($history->is_active)
                                                {{ $history->started_at->diffForHumans(null, true) }} (ongoing)
                                            @else
                                                {{ $history->updated_at->diffForHumans($history->started_at, true) }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $maintenanceHistory->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No maintenance history found.</p>
            @endif
        </div>
    </div>

    <!-- Enable Maintenance Modal -->
    <div id="maintenanceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Enable Maintenance Mode</h3>
                
                <form action="{{ route('admin.maintenance.toggle') }}" method="POST" id="maintenanceForm">
                    @csrf
    <input type="hidden" name="action" value="enable">
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               value="System Maintenance"
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="3" 
                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                  required>We are currently performing scheduled maintenance to improve our services. We'll be back shortly!</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="expected_end_at" class="block text-sm font-medium text-gray-700 mb-2">Expected End Time (Optional)</label>
                        <input type="datetime-local" 
                               name="expected_end_at" 
                               id="expected_end_at" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeMaintenanceModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Enable Maintenance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    function openMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.remove('hidden');
    }
    
    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.add('hidden');
    }
    
    // Ensure form submits properly
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission
        const form = document.getElementById('maintenanceForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Form will submit normally
                console.log('Form submitting...');
            });
        }
    });
    
    // Close modal on outside click
    document.getElementById('maintenanceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMaintenanceModal();
        }
    });
</script>
@endpush
</x-admin-layout>