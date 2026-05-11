<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Activity Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6 overflow-hidden">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Timestamp</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Action</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                    <td class="px-4 py-3 font-medium whitespace-nowrap">
                                        {{ $log->user ? $log->user->name : 'System/Guest' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $log->description ?: '-' }}</td>
                                    <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No activity recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
