<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <div class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-1">Shipping: {{ $shippingCode->code }}</div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Container Detail: {{ $container->container_no }}
                </h2>
            </div>
            <div class="flex gap-3" x-data="">
                @if ($container->status === 'shipping')
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'confirm-complete-hdr')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                        Submit Complete
                    </button>

                    <x-modal name="confirm-complete-hdr" maxWidth="md" focusable>
                        <form method="post" action="{{ route('containers.complete', $container->id) }}" class="p-6 text-left">
                            @csrf
                            <h2 class="text-lg font-medium text-gray-900">Finalize Container</h2>
                            <p class="mt-1 text-sm text-gray-600">Mark [{{ $container->container_no }}] as fully processed? This is immutable.</p>
                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                <x-primary-button class="ms-3 !bg-green-600 hover:!bg-green-700">Set Complete</x-primary-button>
                            </div>
                        </form>
                    </x-modal>
                @endif
                <a href="{{ route('shipping.show', $shippingCode->id) }}" class="bg-white border border-gray-300 py-2 px-4 rounded text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">&larr; Back to Container List</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-hidden p-6 border-t-4 {{ $container->status === 'complete' ? 'border-green-500' : 'border-yellow-500' }}">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Cases within this Container</h3>
                    <span class="px-3 py-1 text-sm font-bold rounded-md {{ $container->status === 'complete' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        Status: {{ strtoupper($container->status) }}
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lot No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parts Types</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($cases as $case)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $case->model ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $case->lot_no ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $case->case_no ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $case->parts()->count() }} Distinct Parts
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('cases.show', $case->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Browse Parts &rarr;</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No cases loaded in this container yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $cases->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
