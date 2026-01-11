<div class="space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-5 border-l-4 border-blue-500 relative">
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

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-5 border-l-4 border-indigo-500 relative">
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

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-5 border-l-4 border-yellow-500 relative">
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

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-5 border-l-4 border-red-500 relative">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Expenses (This Month)</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($monthlyExpenses, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Total Liquid Assets</h3>
            <p class="text-gray-500 text-sm mb-2">Cash available right now (Bank + API Wallets)</p>
            <p class="text-4xl font-extrabold text-green-600">
                {{ number_format($totalBankBalance + $totalApiBalance, 2) }}
            </p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Net Position (Est.)</h3>
            <p class="text-gray-500 text-sm mb-2">Liquid Assets + Pending Settlements - Monthly Expenses</p>
            <p class="text-4xl font-extrabold text-gray-800">
                {{ number_format(($totalBankBalance + $totalApiBalance + $totalUnsettled) - $monthlyExpenses, 2) }}
            </p>
        </div>
    </div>

</div>