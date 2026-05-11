<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shipping Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Upload Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Upload Excel Document</h2>
                    <p class="mt-1 text-sm text-gray-600">Choose an XLSX or CSV file according to the shipping data structure.</p>
                </header>

                <form method="post" action="{{ route('shipping.import') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="file" :value="__('Excel File')" />
                        <input type="file" name="file" id="file" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
                        <x-input-error class="mt-2" :messages="$errors->get('file')" />
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Process Upload') }}</x-primary-button>
                    </div>
                </form>

                @if (session('success'))
                    <p class="mt-4 text-sm font-medium text-green-600">{{ session('success') }}</p>
                @endif
                
                @if (session('error'))
                    <p class="mt-4 text-sm font-medium text-red-600">{{ session('error') }}</p>
                @endif
            </div>

            <!-- Listing Table -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Stored Shipments</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Containers Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded At</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($shippingCodes as $sc)
                            @php
                                $totalContainers = $sc->containers->count();
                                $completedContainers = $sc->containers->where('status', 'complete')->count();
                                $hasCompleted = $completedContainers > 0;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sc->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalContainers }} Container(s)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        @if ($totalContainers > 0 && $completedContainers === $totalContainers)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 font-bold">All Done</span>
                                        @elseif ($completedContainers > 0)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 font-bold">Partial ({{ $completedContainers }}/{{ $totalContainers }})</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800">Not Started</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sc->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3" x-data="">
                                    <a href="{{ route('shipping.export', $sc->id) }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold" title="Download as Excel">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Export
                                    </a>
                                    
                                    <a href="{{ route('shipping.show', $sc->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    
                                    @if (!$hasCompleted)
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'confirm-shipping-del-{{ $sc->id }}')" class="text-red-600 hover:text-red-900 font-semibold ml-3">
                                            Delete
                                        </button>

                                        <x-modal name="confirm-shipping-del-{{ $sc->id }}" maxWidth="md" focusable>
                                            <form method="post" action="{{ route('shipping.destroy', $sc->id) }}" class="p-6 text-left">
                                                @csrf
                                                @method('DELETE')
                                                <h2 class="text-lg font-medium text-red-600">Delete Full Shipment Group?</h2>
                                                <p class="mt-1 text-sm text-gray-600">Warning: This removes Shipping Code [{{ $sc->code }}] and ALL nested components instantly. Confirm removal?</p>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                                    <x-danger-button class="ms-3">Confirm Delete</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @else
                                        <span class="ml-3 text-xs text-gray-400 italic" title="Contains completed data">Immutable</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No data available yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
