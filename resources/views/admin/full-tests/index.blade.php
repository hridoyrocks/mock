{{-- resources/views/admin/full-tests/index.blade.php --}}
<x-admin-layout>
    <x-slot:title>Full Tests Management</x-slot>

    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Full Tests</h1>
            <a href="{{ route('admin.full-tests.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Create Full Test
            </a>
        </div>

        <!-- Full Tests Table -->
        <div class="glass rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Sections</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Attempts</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700" id="sortable-tests">
                        @forelse($fullTests as $fullTest)
                            <tr class="hover:bg-gray-800/50 transition-colors sortable-row" data-id="{{ $fullTest->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-grip-vertical text-gray-500 mr-2 cursor-move handle"></i>
                                        <span class="text-white">{{ $fullTest->order_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-white font-medium">{{ $fullTest->title }}</div>
                                        @if($fullTest->description)
                                            <div class="text-gray-400 text-sm">{{ Str::limit($fullTest->description, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($fullTest->is_premium)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                            <i class="fas fa-crown mr-1"></i>
                                            Premium
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                            Free
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($fullTest->hasAllSections())
                                            <span class="text-green-400 text-sm">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                All sections assigned
                                            </span>
                                        @else
                                            <span class="text-red-400 text-sm">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Missing sections
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-white">{{ $fullTest->attempts()->count() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($fullTest->active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.full-tests.show', $fullTest) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.full-tests.edit', $fullTest) }}" 
                                           class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.full-tests.toggle-status', $fullTest) }}" 
                                              method="POST" 
                                              class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-purple-400 hover:text-purple-300 transition-colors"
                                                    title="{{ $fullTest->active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        @if($fullTest->attempts()->count() === 0)
                                            <form action="{{ route('admin.full-tests.destroy', $fullTest) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this full test?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-300 transition-colors"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <i class="fas fa-file-alt text-4xl mb-4"></i>
                                        <p>No full tests found. Create your first full test!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $fullTests->links() }}
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortable = new Sortable(document.getElementById('sortable-tests'), {
                handle: '.handle',
                animation: 150,
                onEnd: function(evt) {
                    const ids = Array.from(evt.target.children).map(row => row.dataset.id);
                    
                    fetch('{{ route('admin.full-tests.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ ids })
                    });
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
