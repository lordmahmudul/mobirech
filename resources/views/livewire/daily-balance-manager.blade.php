<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Daily Balances') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            @if (session()->has('message'))
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-4">
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            @endif

            <div class="mb-4">
                <x-button wire:click="create">
                    {{ __('Add New Balance Record') }}
                </x-button>
            </div>

            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Bank Name</th>
                        <th class="px-4 py-2 text-right">Available Balance</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyBalances as $record)
                    <tr>
                        <td class="border px-4 py-2">{{ $record->balance_date->format('d M, Y') }}</td>
                        <td class="border px-4 py-2">{{ $record->bank->bank_name ?? 'Unknown' }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($record->available_balance, 2) }}</td>
                        <td class="border px-4 py-2 text-center">
                            <x-button wire:click="edit({{ $record->id }})" class="bg-blue-500 hover:bg-blue-700">
                                Edit
                            </x-button>
                            <x-danger-button wire:click="delete({{ $record->id }})" wire:confirm="Are you sure?">
                                Delete
                            </x-danger-button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-4">
                {{ $dailyBalances->links() }}
            </div>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">
                    {{ $balance_id ? 'Edit Balance Record' : 'Add Balance Record' }}
                </x-slot>

                <x-slot name="content">
                    
                    <div class="mb-4">
                        <x-label for="bank_id" value="{{ __('Select Bank') }}" />
                        <select id="bank_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="bank_id">
                            <option value="">-- Choose Bank --</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                        @error('bank_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="balance_date" value="{{ __('Date') }}" />
                        <x-input id="balance_date" type="date" class="mt-1 block w-full" wire:model="balance_date" />
                        @error('balance_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="available_balance" value="{{ __('Available Balance') }}" />
                        <x-input id="available_balance" type="number" step="0.01" class="mt-1 block w-full" wire:model="available_balance" />
                        @error('available_balance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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