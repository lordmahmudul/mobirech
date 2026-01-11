<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Expenses') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message')) <div class="bg-green-100 p-4 mb-4 text-green-700">{{ session('message') }}</div> @endif

            <x-button wire:click="create" class="mb-4">Add Expense</x-button>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Date</th>
                            <th class="p-2 text-left">Category</th>
                            <th class="p-2 text-left">Paid Via</th>
                            <th class="p-2 text-left">Ref No.</th>
                            <th class="p-2 text-right">Amount</th>
                            <th class="p-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $exp)
                        <tr class="border-b">
                            <td class="p-2">{{ $exp->expense_date->format('d M, Y') }}</td>
                            <td class="p-2 font-bold">{{ $exp->category->name }}</td>
                            <td class="p-2 text-sm text-gray-600">{{ $exp->bank->bank_name ?? 'Cash/Other' }}</td>
                            <td class="p-2 text-sm">{{ $exp->reference_no }}</td>
                            <td class="p-2 text-right font-bold text-red-600">-{{ number_format($exp->amount, 2) }}</td>
                            <td class="p-2 text-center">
                                <x-button wire:click="edit({{ $exp->id }})" class="bg-blue-500 text-xs">Edit</x-button>
                                <x-danger-button wire:click="delete({{ $exp->id }})" class="text-xs">Del</x-danger-button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $expenses->links() }}</div>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">{{ $expense_id ? 'Edit Expense' : 'Add Expense' }}</x-slot>
                <x-slot name="content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div class="col-span-2 md:col-span-1">
                            <x-label for="expense_date" value="Date" />
                            <x-input id="expense_date" type="date" class="w-full mt-1" wire:model="expense_date" />
                            @error('expense_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <x-label for="amount" value="Amount" />
                            <x-input id="amount" type="number" step="0.01" class="w-full mt-1" wire:model="amount" />
                            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <x-label for="expense_category_id" value="Category" />
                            <select wire:model="expense_category_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('expense_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <x-label for="bank_id" value="Paid From (Bank) - Optional" />
                            <select wire:model="bank_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">None (Cash/Other)</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2">
                            <x-label for="description" value="Description / Note" />
                            <x-input id="description" type="text" class="w-full mt-1" wire:model="description" />
                        </div>

                        <div class="col-span-2">
                            <x-label for="reference_no" value="Reference No (Invoice #)" />
                            <x-input id="reference_no" type="text" class="w-full mt-1" wire:model="reference_no" />
                        </div>

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