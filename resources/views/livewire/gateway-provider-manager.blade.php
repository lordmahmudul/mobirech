<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gateway Providers') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('message') }}</div>
            @endif

            <x-button wire:click="create" class="mb-4">{{ __('Add Gateway') }}</x-button>

            <table class="min-w-full border-collapse block md:table">
                <thead class="block md:table-header-group">
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left font-bold">Name</th>
                        <th class="p-2 text-left font-bold">Status</th>
                        <th class="p-2 text-center font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    @foreach($providers as $provider)
                    <tr class="bg-white border-b">
                        <td class="p-2">{{ $provider->provider_name }}</td>
                        <td class="p-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $provider->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $provider->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-2 text-center">
                            <x-button wire:click="edit({{ $provider->id }})" class="bg-blue-500">Edit</x-button>
                            <x-danger-button wire:click="delete({{ $provider->id }})">Delete</x-danger-button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">{{ $provider_id ? 'Edit Gateway' : 'Add Gateway' }}</x-slot>
                <x-slot name="content">
                    <div class="mb-4">
                        <x-label for="provider_name" value="Gateway Name" />
                        <x-input id="provider_name" type="text" class="block w-full mt-1" wire:model="provider_name" />
                        @error('provider_name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="form-checkbox h-5 w-5 text-gray-600">
                            <span class="ml-2 text-gray-700">Is Active?</span>
                        </label>
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-secondary-button wire:click="closeModal">Cancel</x-secondary-button>
                    <x-button class="ml-2" wire:click="store">Save</x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>