<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex gap-2 text-xs font-bold text-gray-400 uppercase mb-1">
                    <span>Code: {{ $shippingCode->code }}</span>
                    <span>/</span>
                    <span>Cont: {{ $container->container_no }}</span>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Case Details: [{{ $case->model ?: 'N/A' }} - {{ $case->case_no ?: 'N/A' }}]
                </h2>
            </div>
            <a href="{{ route('containers.show', $container->id) }}" class="bg-white border border-gray-300 py-2 px-4 rounded text-sm font-medium shadow-sm hover:bg-gray-50">&larr; Back to Container</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="mb-4 flex flex-wrap gap-6 text-sm text-gray-700 bg-gray-50 p-4 rounded-md border">
                    <div><span class="font-semibold text-gray-900">Model:</span> {{ $case->model ?: 'Not Specified' }}</div>
                    <div><span class="font-semibold text-gray-900">Lot Number:</span> {{ $case->lot_no ?: 'Not Specified' }}</div>
                    <div><span class="font-semibold text-gray-900">Case Number:</span> {{ $case->case_no ?: 'Not Specified' }}</div>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-4">Part Items List</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Part No</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">QTY</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Unit Wgt</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Net Wgt</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($parts as $part)
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-800">{{ $part->parts_no }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $part->parts_name ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-gray-900 font-semibold">{{ $part->qty }}</td>
                                <td class="px-4 py-3 text-right text-gray-500">{{ number_format($part->unit_weight, 6) }}</td>
                                <td class="px-4 py-3 text-right text-gray-900 font-bold">{{ number_format($part->net_weight, 6) }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap space-x-3" x-data="">
                                    @if ($container->status !== 'complete')
                                        <a href="{{ route('parts.edit', $part->id) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-2">Edit</a>
                                        
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'del-part-{{ $part->id }}')" class="text-red-500 hover:text-red-800 text-xs uppercase tracking-wider font-bold">
                                            Del
                                        </button>

                                        <x-modal name="del-part-{{ $part->id }}" maxWidth="sm" focusable>
                                            <form method="post" action="{{ route('parts.destroy', $part->id) }}" class="p-6 text-left">
                                                @csrf
                                                @method('DELETE')
                                                <h2 class="text-lg font-medium text-gray-900">Delete Part?</h2>
                                                <p class="mt-1 text-sm text-gray-600">Delete [{{ $part->parts_no }}] from this Case record?</p>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                                    <x-danger-button class="ms-3">Delete</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Read Only</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400 italic">No parts records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $parts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
