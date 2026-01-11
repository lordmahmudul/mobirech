<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gateway Daily Reports') }}</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('message') }}</div>
            @endif

            <x-button wire:click="create" class="mb-4">{{ __('Add Report') }}</x-button>

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Gateway</th>
                            <th class="px-4 py-2 text-right">Collected</th>
                            <th class="px-4 py-2 text-right">Settled</th>
                            <th class="px-4 py-2 text-right">Unsettled</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td class="border px-4 py-2">{{ $report->report_date->format('d M, Y') }}</td>
                            <td class="border px-4 py-2">{{ $report->provider->provider_name }}</td>
                            <td class="border px-4 py-2 text-right font-bold text-blue-600">{{ number_format($report->amount_collected, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-green-600">{{ number_format($report->amount_settled, 2) }}</td>
                            <td class="border px-4 py-2 text-right text-red-600">{{ number_format($report->amount_unsettled, 2) }}</td>
                            <td class="border px-4 py-2 text-center">
                                <x-button wire:click="edit({{ $report->id }})" class="text-xs bg-blue-500">Edit</x-button>
                                <x-danger-button wire:click="delete({{ $report->id }})" class="text-xs">Del</x-danger-button>
                            </td>
                        </tr>
                        @endforeach
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
                            <x-input id="report_date" type="date" class="block w-full mt-1" wire:model="report_date" />
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
