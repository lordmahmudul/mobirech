<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('API Recharge Stats') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('message') }}</div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <x-button wire:click="create">{{ __('Add Stats') }}</x-button>

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
                <table class="table-auto w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2 text-left">Date</th>
                            <th class="border px-4 py-2 text-left">Provider</th>
                            <th class="border px-4 py-2 text-right">API Amount</th>
                            <th class="border px-4 py-2 text-right">DB Amount</th>
                            <th class="border px-4 py-2 text-right">Differences Amount</th>
                            <th class="border px-4 py-2 text-right">API Count</th>
                            <th class="border px-4 py-2 text-right">DB Count</th>
                            <th class="border px-4 py-2 text-right">Differences Count</th>
                            <th class="border px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats as $stat)
                        @php
                            $diffAmt = $stat->api_success_amount - $stat->db_success_amount;
                            $diffCnt = $stat->api_success_count - $stat->db_success_count;
                        @endphp
                        <tr >
                            <td class="border px-4 py-2">{{ $stat->report_date->format('d M, Y') }}</td>
                            <td class="border px-4 py-2 font-medium">{{ $stat->provider->provider_name }}</td>
                            <td class="border px-4 py-2 text-right text-green-700 font-bold">{{ number_format($stat->api_success_amount, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-blue-700 font-bold">{{ number_format($stat->db_success_amount, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-blue-700 font-bold">{{ number_format($diffAmt, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-green-600">{{ number_format($stat->api_success_count) }}</td>
                            <td class="border px-4 py-2 text-right text-blue-600">{{ number_format($stat->db_success_count) }}</td>
                            <td class="border px-4 py-2 text-right text-blue-600">{{ number_format($diffCnt) }}</td>
                            <td class="border px-4 py-2 text-center">
                                <x-button wire:click="edit({{ $stat->id }})" class="text-xs bg-blue-500">Edit</x-button>
                                
                            </td>
                        </tr>
                        
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                No stats found for this period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($stats->count())
        <tfoot class="bg-gray-50 font-semibold">
            <tr class="border-t">
                <td class="px-4 py-3">Total</td>
                <td></td>

                <td class="px-4 py-3 text-right ">
                    {{ number_format($totalApiAmount, 2) }}
                </td>

                <td class="px-4 py-3 text-right ">
                    {{ number_format($totalDbAmount, 2) }}
                </td>

                <td class="px-4 py-3 text-right ">
                    {{ number_format($totalDiffAmount, 2) }}
                </td>

                <td class="px-4 py-3 text-right ">
                    {{ number_format($totalApiCount) }}
                </td>

                <td class="px-4 py-3 text-right ">
                    {{ number_format($totalDbCount) }}
                </td>

                <td class="px-4 py-3 text-right ">
                    {{ number_format($totalDiffCount) }}
                </td>

                <td></td>
            </tr>
        </tfoot>
        @endif
                </table>
            </div>
            
            <div class="mt-4">{{ $stats->links() }}</div>

            <x-dialog-modal wire:model="isModalOpen">
                <x-slot name="title">{{ $stat_id ? 'Edit Stats' : 'Add Stats' }}</x-slot>
                <x-slot name="content">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <x-input id="report_date" type="date" max="{{ date('Y-m-d') }}" class="block w-full mt-1" wire:model="report_date" />
                                @error('report_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="border-gray-200 my-2">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="api_success_amount" value="API Success Amount" />
                                <x-input id="api_success_amount" type="number" step="0.01" class="block w-full mt-1" wire:model="api_success_amount" />
                                @error('api_success_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label for="api_success_count" value="API Success Count" />
                                <x-input id="api_success_count" type="number" class="block w-full mt-1" wire:model="api_success_count" />
                                @error('api_success_count') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="db_success_amount" value="DB Success Amount" />
                                <x-input id="db_success_amount" type="number" step="0.01" class="block w-full mt-1" wire:model="db_success_amount" />
                                @error('db_success_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label for="db_success_count" value="DB Success Count" />
                                <x-input id="db_success_count" type="number" class="block w-full mt-1" wire:model="db_success_count" />
                                @error('db_success_count') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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