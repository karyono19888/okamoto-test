<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Part: {{ $part->parts_no }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-8">
                <form method="POST" action="{{ route('parts.update', $part->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="parts_no" :value="__('Parts No')" />
                        <x-text-input id="parts_no" name="parts_no" type="text" class="mt-1 block w-full" :value="old('parts_no', $part->parts_no)" required />
                        <x-input-error :messages="$errors->get('parts_no')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="parts_name" :value="__('Parts Name')" />
                        <x-text-input id="parts_name" name="parts_name" type="text" class="mt-1 block w-full" :value="old('parts_name', $part->parts_name)" />
                        <x-input-error :messages="$errors->get('parts_name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="qty" :value="__('QTY')" />
                            <x-text-input id="qty" name="qty" type="number" class="mt-1 block w-full" :value="old('qty', $part->qty)" required />
                            <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="unit_weight" :value="__('Unit Weight')" />
                            <x-text-input id="unit_weight" name="unit_weight" type="number" step="0.000001" class="mt-1 block w-full" :value="old('unit_weight', $part->unit_weight)" required />
                            <x-input-error :messages="$errors->get('unit_weight')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="net_weight" :value="__('Net Weight')" />
                            <x-text-input id="net_weight" name="net_weight" type="number" step="0.000001" class="mt-1 block w-full" :value="old('net_weight', $part->net_weight)" required />
                            <x-input-error :messages="$errors->get('net_weight')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-3">
                        <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline text-sm">Cancel</a>
                        <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
