<div class="space-y-6">
    
    <div class="flex md:flex-row justify-between items-center bg-white p-6 rounded-lg shadow gap-4">
        <h3 class="text-lg font-semibold text-gray-700">Financial Overview</h3>
        
        <div class="flex md:flex-row gap-3 items-center">
            
            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="customStartDate" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <span class="text-gray-500 font-medium">-</span>
                <input type="date" wire:model.live="customEndDate" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <select wire:model.live="dateFilter" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="this_week">This Week</option>
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="custom_range">Custom Range</option>
            </select>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-4 border-blue-500 relative">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Bank Balance</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($totalBankBalance, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-4 border-indigo-500 relative">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">API Wallet Funds</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($totalApiBalance, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-4 border-yellow-500 relative">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Settlements</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($totalUnsettled, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-4 border-red-500 relative">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Expenses
                        </dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($monthlyExpenses, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Total Liquid Assets</h3>
            <p class="text-gray-500 text-sm mb-2">Cash available right now (Bank + API Wallets + Pending Settlements)</p>
            <p class="text-4xl font-extrabold text-green-600">
                {{ number_format($totalBankBalance + $totalApiBalance + $totalUnsettled, 2) }}
            </p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Net Position (Est.)</h3>
            <p class="text-gray-500 text-sm mb-2">Liquid Assets - Expenses</p>
            <p class="text-4xl font-extrabold text-gray-800">
                {{ number_format(($totalBankBalance + $totalApiBalance + $totalUnsettled) - $monthlyExpenses, 2) }}
            </p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">Success Recharge Report (API vs DB)</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 uppercase text-xs leading-normal text-gray-600">
                        <th class="border px-4 py-3 text-left">Date</th>
                        <th class="border px-4 py-3 text-left">Provider</th>
                        <th class="border px-4 py-3 text-right bg-green-50">API Success</th>
                        <th class="border px-4 py-3 text-right bg-blue-50">DB Success</th>
                        <th class="border px-4 py-3 text-right bg-red-50 font-bold ">Success Difference</th>
                        <th class="border px-4 py-3 text-right">API Count</th>
                        <th class="border px-4 py-3 text-right">DB Count</th>
                        <th class="border px-4 py-3 text-right font-bold ">Count Difference</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($rechargeStats as $stat)
                    @php
                        $diffAmt = $stat->api_success_amount - $stat->db_success_amount;
                        $diffCnt = $stat->api_success_count - $stat->db_success_count;
                        // Highlight row if there is a difference
                        $rowClass = ($diffAmt != 0 || $diffCnt != 0) ? 'bg-red-50' : 'hover:bg-gray-50 border-b';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="border px-4 py-3">{{ $stat->report_date->format('d M, Y') }}</td>
                        <td class="border px-4 py-3 font-medium">{{ $stat->provider->provider_name }}</td>
                        
                        <td class="border px-4 py-3 text-center font-medium text-green-700 bg-green-50">{{ number_format($stat->api_success_amount, 2) }}</td>
                        <td class="border px-4 py-3 text-center font-medium text-blue-700 bg-blue-50">{{ number_format($stat->db_success_amount, 2) }}</td>
                        
                        <td class="border px-4 py-3 text-center font-bold bg-red-50 {{ $diffAmt != 0 ? 'text-red-600' : 'text-gray-300' }}">
                            {{ number_format($diffAmt, 2) }}
                        </td>

                        <td class="border px-4 py-3 text-center">{{ number_format($stat->api_success_count) }}</td>
                        <td class="border px-4 py-3 text-center">{{ number_format($stat->db_success_count) }}</td>
                        
                        <td class="border px-4 py-3 text-center font-bold {{ $diffCnt != 0 ? 'text-red-600' : 'text-gray-300' }}">
                            {{ number_format($diffCnt) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            No recharge stats found for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
            <tr class="border-t">
                <td class="px-4 py-3">Total</td>
                <td class="border border-r-1 border-gray-100"></td>
                <td class="px-4 py-3 text-center font-bold bg-green-50">{{ number_format($totalRechargeApiAmt, 2) }}</td>
                <td class="px-4 py-3 text-center font-bold bg-blue-50">{{ number_format($totalRechargeDbAmt, 2) }}</td>
                <td class="px-4 py-3 text-center font-bold bg-red-50 {{ $totalRechargeDiffAmt != 0 ? 'text-red-600' : 'text-gray-300' }}">
                    {{ number_format($totalRechargeDiffAmt, 2) }}
                </td>
                <td class="px-4 py-3 text-center font-bold">{{ number_format($rechargeStats->sum('api_success_count')) }}</td>
                <td class="px-4 py-3 text-center font-bold">{{ number_format($rechargeStats->sum('db_success_count')) }}</td>
                <td class="px-4 py-3 text-center font-bold {{ $rechargeStats->sum('api_success_count') != $rechargeStats->sum('db_success_count') ? 'text-red-600' : 'text-gray-300' }}">
                    {{ number_format($rechargeStats->sum('api_success_count') - $rechargeStats->sum('db_success_count')) }}
                </td>
            </tr>
            </table>
        </div>
    </div>

</div>