<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Containers for: {{ $shippingCode->code }}
            </h2>
            <a href="{{ route('shipping.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Shipments</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Containers Listing</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Container No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cases Count</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($containers as $container)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-700">
                                    {{ $container->container_no }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $container->status === 'complete' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($container->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $container->cases()->count() }} Case(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4" x-data="">
                                    @if ($container->status === 'shipping')
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'confirm-complete-{{ $container->id }}')" class="text-green-600 hover:text-green-900 font-bold mr-3">
                                            Complete
                                        </button>
                                        
                                        <x-modal name="confirm-complete-{{ $container->id }}" maxWidth="md" focusable>
                                            <form method="post" action="{{ route('containers.complete', $container->id) }}" class="p-6 text-left">
                                                @csrf
                                                <h2 class="text-lg font-medium text-gray-900">Complete Container Confirmation</h2>
                                                <p class="mt-1 text-sm text-gray-600">Are you absolutely sure you want to set Container No [{{ $container->container_no }}] to complete? This action is final.</p>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                                    <x-primary-button class="ms-3 !bg-green-600 hover:!bg-green-700">Yes, Complete</x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endif

                                    <a href="{{ route('containers.show', $container->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold mr-2">View Cases &rarr;</a>
                                    
                                    @if ($container->status !== 'complete')
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-{{ $container->id }}')" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase">
                                            Delete
                                        </button>

                                        <x-modal name="confirm-delete-{{ $container->id }}" maxWidth="md" focusable>
                                            <form method="post" action="{{ route('containers.destroy', $container->id) }}" class="p-6 text-left">
                                                @csrf
                                                @method('DELETE')
                                                <h2 class="text-lg font-medium text-red-700">Delete Confirmation</h2>
                                                <p class="mt-1 text-sm text-gray-600">Caution: This will permanently wipe the entire Container [{{ $container->container_no }}] along with ALL its children Cases and Parts. Type 'delete' is not needed, just confirm.</p>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                                    <x-danger-button class="ms-3">Confirm Delete</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Locked</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No containers found under this Shipping Code.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $containers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
