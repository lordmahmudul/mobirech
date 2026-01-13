<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gateway Daily Reports') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Total Collected') }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalCollected, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Total Settled') }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSettled, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Pending Settlement') }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalUnsettled, 2) }}</p>
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
                            <th class="px-4 py-2 text-left">Gateway</th>
                            <th class="px-4 py-2 text-right">Collected</th>
                            <th class="px-4 py-2 text-right">Settled</th>
                            <th class="px-4 py-2 text-right">Unsettled</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="border px-4 py-2">{{ $report->report_date->format('d M, Y') }}</td>
                            <td class="border px-4 py-2 font-medium">{{ $report->provider->provider_name }}</td>
                            <td class="border px-4 py-2 text-right text-blue-600 font-semibold">{{ number_format($report->amount_collected, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-green-600 font-semibold">{{ number_format($report->amount_settled, 2) }}</td>
                            <td class="border px-4 py-2 text-right font-bold text-red-600">{{ number_format($report->amount_unsettled, 2) }}</td>
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
                            <x-label for="gateway_provider_id" value="Gateway" />
                            <select id="gateway_provider_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" wire:model="gateway_provider_id">
                                <option value="">Select Gateway</option>
                                @foreach($providers as $p)
                                    <option value="{{ $p->id }}">{{ $p->provider_name }}</option>
                                @endforeach
                            </select>
                            @error('gateway_provider_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <x-label for="report_date" value="Date" />
                            <x-input id="report_date" type="date" max="{{ date('Y-m-d') }}" class="block w-full mt-1" wire:model="report_date" />
                            @error('report_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <x-label for="amount_collected" value="Collected" />
                                <x-input id="amount_collected" type="number" step="0.01" class="block w-full mt-1" wire:model="amount_collected" />
                                @error('amount_collected') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label for="amount_settled" value="Settled" />
                                <x-input id="amount_settled" type="number" step="0.01" class="block w-full mt-1" wire:model="amount_settled" />
                                @error('amount_settled') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label for="amount_unsettled" value="Unsettled" />
                                <x-input id="amount_unsettled" type="number" step="0.01" class="block w-full mt-1" wire:model="amount_unsettled" />
                                @error('amount_unsettled') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
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