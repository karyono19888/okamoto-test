<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="px-4 sm:px-0">
                <p class="text-sm text-gray-500">Ringkasan data shipping yang sudah tersimpan di sistem.</p>
            </div>

            @php
                $statCards = [
                    [
                        'label' => 'Jumlah Container',
                        'value' => $summary['containers'],
                        'helper' => 'Total container',
                        'classes' => 'bg-blue-50 text-blue-700 ring-blue-100',
                        'icon' => 'M3 7.5 12 3l9 4.5m-18 0 9 4.5m-9-4.5v9l9 4.5m0-13.5v13.5m0-13.5 9-4.5m-9 18 9-4.5v-9',
                    ],
                    [
                        'label' => 'Jumlah Case',
                        'value' => $summary['cases'],
                        'helper' => 'Total case',
                        'classes' => 'bg-amber-50 text-amber-700 ring-amber-100',
                        'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0A2.25 2.25 0 0018 5.25H6A2.25 2.25 0 003.75 7.5m16.5 0v.243a2.25 2.25 0 01-1.07 1.916l-6 3.75a2.25 2.25 0 01-2.36 0l-6-3.75a2.25 2.25 0 01-1.07-1.916V7.5',
                    ],
                    [
                        'label' => 'Jumlah Part',
                        'value' => $summary['parts'],
                        'helper' => 'Total part',
                        'classes' => 'bg-sky-50 text-sky-700 ring-sky-100',
                        'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.379.138.751.43.992l1.005.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.075-.124l-1.217.456a1.125 1.125 0 01-1.37-.49l-1.296-2.247a1.125 1.125 0 01.26-1.431l1.003-.827c.293-.241.438-.613.431-.992a6.759 6.759 0 010-.255c.007-.379-.138-.751-.43-.992l-1.005-.827a1.125 1.125 0 01-.26-1.43l1.298-2.247a1.125 1.125 0 011.369-.491l1.217.456c.355.133.75.072 1.076-.124.072-.044.146-.087.22-.128.331-.183.581-.495.644-.869l.213-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    ],
                    [
                        'label' => 'Container Complete',
                        'value' => $summary['completedContainers'],
                        'helper' => 'Status complete',
                        'classes' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                        'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($statCards as $card)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($card['value']) }}</p>
                                </div>
                                <div class="rounded-lg p-3 ring-1 {{ $card['classes'] }}">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                                    </svg>
                                </div>
                            </div>

                            <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $card['helper'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Shipping Management</h3>
                        <p class="mt-1 text-sm text-gray-500">Upload data baru atau lihat detail shipping yang sudah tersimpan.</p>
                    </div>
                    <a href="{{ route('shipping.index') }}" class="inline-flex items-center justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        Buka Shipping
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
