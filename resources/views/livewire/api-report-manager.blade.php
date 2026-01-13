<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('API Daily Reports') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Total Added') }}</p>
                        <p class="text-2xl font-bold text-gray-800">+{{ number_format($totalAdded, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Total Used') }}</p>
                        <p class="text-2xl font-bold text-gray-800">-{{ number_format($totalUsed, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Closing Balance') }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalAvailable, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('message') }}</div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <x-button wire:click="create" >{{ __('Add Daily Report') }}</x-button>

                <div>
                    <select wire:model.live="filterProvider" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Providers</option>
                        @foreach($providers as $p)
                            <option value="{{ $p->id }}">{{ $p->provider_name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="dateFilter" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="last_month">Last Month</option>
                        <option value="all">All History</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Provider</th>
                            <th class="px-4 py-2 text-right">Added</th>
                            <th class="px-4 py-2 text-right">Used</th>
                            <th class="px-4 py-2 text-right">Available</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="border px-4 py-2">{{ $report->report_date->format('d M, Y') }}</td>
                            <td class="border px-4 py-2 font-medium">{{ $report->provider->provider_name }}</td>
                            <td class="border px-4 py-2 text-right text-green-600 font-semibold">+{{ number_format($report->balance_added, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-red-600 font-semibold">-{{ number_format($report->balance_used, 2) }}</td>
                            <td class="border px-4 py-2 text-right font-bold text-gray-800">{{ number_format($report->available_balance, 2) }}</td>
                            <td class="border px-4 py-2 text-center">
                                <x-button wire:click="edit({{ $report->id }})" class="text-xs bg-blue-500">Edit</x-button>
                                <x-danger-button wire:click="delete({{ $report->id }})" class="text-xs">Del</x-danger-button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="border px-4 py-8 text-center text-gray-400">
                                No reports found for this period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">{{ $reports->links() }}</div>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">{{ $report_id ? 'Edit Report' : 'Add Report' }}</x-slot>
                <x-slot name="content">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-label for="api_provider_id" value="Provider" />
                            <select id="api_provider_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" wire:model="api_provider_id">
                                <option value="">Select Provider</option>
                                @foreach($providers as $p)
                                    <option value="{{ $p->id }}">{{ $p->provider_name }}</option>
                                @endforeach
                            </select>
                            @error('api_provider_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <x-label for="report_date" value="Date" />
                            <x-input id="report_date" type="date" class="block w-full mt-1" wire:model="report_date" />
                            @error('report_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="balance_added" value="Balance Added" />
                                <x-input id="balance_added" type="number" step="0.01" class="block w-full mt-1" wire:model="balance_added" />
                                @error('balance_added') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label for="balance_used" value="Balance Used" />
                                <x-input id="balance_used" type="number" step="0.01" class="block w-full mt-1" wire:model="balance_used" />
                                @error('balance_used') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <x-label for="available_balance" value="Closing Available Balance" />
                            <x-input id="available_balance" type="number" step="0.01" class="block w-full mt-1" wire:model="available_balance" />
                            @error('available_balance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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