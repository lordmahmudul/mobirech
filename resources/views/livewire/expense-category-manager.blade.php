<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Expense Categories') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message')) <div class="bg-green-100 p-4 mb-4 text-green-700">{{ session('message') }}</div> @endif

            <x-button wire:click="create" class="mb-4">Add Category</x-button>

            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Name</th>
                        <th class="p-2 text-left">Description</th>
                        <th class="p-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr class="border-b">
                        <td class="p-2">{{ $cat->name }}</td>
                        <td class="p-2">{{ $cat->description }}</td>
                        <td class="p-2 text-center">
                            <x-button wire:click="edit({{ $cat->id }})" class="bg-blue-500">Edit</x-button>
                            <x-danger-button wire:click="delete({{ $cat->id }})">Del</x-danger-button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">{{ $category_id ? 'Edit Category' : 'Add Category' }}</x-slot>
                <x-slot name="content">
                    <div class="mb-4">
                        <x-label for="name" value="Name" />
                        <x-input id="name" type="text" class="w-full mt-1" wire:model="name" />
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <x-label for="description" value="Description" />
                        <x-input id="description" type="text" class="w-full mt-1" wire:model="description" />
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