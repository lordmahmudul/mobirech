<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bank;
use App\Models\ApiProvider;
use App\Models\GatewayDailyReport;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $totalBankBalance = 0;
    public $totalApiBalance = 0;
    public $totalUnsettled = 0;
    public $monthlyExpenses = 0;

    public function mount()
    {
        // 1. Sum of all Bank Balances (UPDATED)
        // We get all banks, include their latest balance record, and sum the 'available_balance' column.
        $this->totalBankBalance = Bank::with('latestDailyBalance')->get()->sum(function ($bank) {
            // If a bank has a record, add its balance; otherwise add 0
            return $bank->latestDailyBalance ? $bank->latestDailyBalance->available_balance : 0;
        });

        // 2. Sum of all API Provider Wallets
        // (Make sure your ApiProvider model actually has a 'current_balance' column, otherwise follow the same logic as above)
        $this->totalApiBalance = ApiProvider::sum('current_balance');

        // 3. Total Unsettled Amount
        $this->totalUnsettled = GatewayDailyReport::whereDate('report_date', Carbon::today())
                                ->sum('amount_unsettled');

        // 4. Expenses for the Current Month
        $this->monthlyExpenses = Expense::whereMonth('expense_date', Carbon::now()->month)
                                ->whereYear('expense_date', Carbon::now()->year)
                                ->sum('amount');
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}