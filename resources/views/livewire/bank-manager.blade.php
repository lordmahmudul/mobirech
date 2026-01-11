<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Banks') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            @if (session()->has('message'))
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-4">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <x-button wire:click="create">
                    {{ __('Add New Bank') }}
                </x-button>
            </div>

            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 w-20">No.</th>
                        <th class="px-4 py-2">Bank Name</th>
                        <th class="px-4 py-2">Account Number</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banks as $bank)
                    <tr>
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $bank->bank_name }}</td>
                        <td class="border px-4 py-2">{{ $bank->account_number ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">
                            <x-button wire:click="edit({{ $bank->id }})" class="bg-blue-500 hover:bg-blue-700">
                                Edit
                            </x-button>
                            <x-danger-button wire:click="delete({{ $bank->id }})" wire:confirm="Are you sure you want to delete this bank?">
                                Delete
                            </x-danger-button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">
                    {{ $bank_id ? 'Edit Bank' : 'Create Bank' }}
                </x-slot>

                <x-slot name="content">
                    <div class="mb-4">
                        <x-label for="bank_name" value="{{ __('Bank Name') }}" />
                        <x-input id="bank_name" type="text" class="mt-1 block w-full" wire:model="bank_name" />
                        @error('bank_name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="account_number" value="{{ __('Account Number') }}" />
                        <x-input id="account_number" type="text" class="mt-1 block w-full" wire:model="account_number" />
                        @error('account_number') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ml-3" wire:click="store" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>
</div>