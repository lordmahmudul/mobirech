<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bank;
use App\Models\ApiProvider;
use App\Models\GatewayProvider;
use App\Models\GatewayDailyReport;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $totalBankBalance = 0;
    public $totalApiBalance = 0;
    public $totalUnsettled = 0;
    public $monthlyExpenses = 0;
    
    public $dateFilter = 'yesterday'; 
    public $customStartDate;
    public $customEndDate;

    public function mount()
    {
        // Initialize with Yesterday's date
        $this->customStartDate = Carbon::yesterday()->format('Y-m-d');
        $this->customEndDate = Carbon::yesterday()->format('Y-m-d');
        
        $this->calculateStats();
    }

    // 1. When Dropdown Changes -> Update Date Inputs
    public function updatedDateFilter()
    {
        switch ($this->dateFilter) {
            case 'today':
                $this->customStartDate = Carbon::today()->format('Y-m-d');
                $this->customEndDate = Carbon::today()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->customStartDate = Carbon::yesterday()->format('Y-m-d');
                $this->customEndDate = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'this_week':
                $this->customStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->customEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->customStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->customEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->customStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->customEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
        }
        
        $this->calculateStats();
    }

    // 2. When User Changes Date Inputs -> Switch Dropdown to "Custom"
    public function updatedCustomStartDate()
    {
        $this->dateFilter = 'custom_range';
        $this->calculateStats();
    }

    public function updatedCustomEndDate()
    {
        $this->dateFilter = 'custom_range';
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Source of Truth is now ALWAYS the custom date variables
        $startDate = Carbon::parse($this->customStartDate)->startOfDay();
        $endDate = Carbon::parse($this->customEndDate)->endOfDay();

        // ---------------------------------------------------------
        // 1. Bank Balances (Balance As Of Logic)
        // ---------------------------------------------------------
        $this->totalBankBalance = 0;
        $bankIds = Bank::pluck('id');

        foreach ($bankIds as $bankId) {
            $latestBalance = \App\Models\BankDailyBalance::where('bank_id', $bankId)
                ->where('balance_date', '<=', $endDate)
                ->orderBy('balance_date', 'desc')
                ->value('available_balance');
            
            $this->totalBankBalance += ($latestBalance ?? 0);
        }

        // ---------------------------------------------------------
        // 2. API Balance (Balance As Of Logic)
        // ---------------------------------------------------------
        $this->totalApiBalance = 0;
        $apiIds = ApiProvider::pluck('id');

        foreach ($apiIds as $apiId) {
            $latestApiBalance = \App\Models\ApiProviderDailyReport::where('api_provider_id', $apiId)
                ->where('report_date', '<=', $endDate)
                ->orderBy('report_date', 'desc')
                ->value('available_balance');
            
            $this->totalApiBalance += ($latestApiBalance ?? 0);
        }

        // ---------------------------------------------------------
        // 3. Gateway Unsettled (Balance As Of Logic)
        // ---------------------------------------------------------
        $this->totalUnsettled = 0;
        $gatewayIds = GatewayProvider::pluck('id');

        foreach ($gatewayIds as $gatewayId) {
            $latestUnsettled = GatewayDailyReport::where('gateway_provider_id', $gatewayId)
                ->where('report_date', '<=', $endDate)
                ->orderBy('report_date', 'desc')
                ->value('amount_unsettled');
            
            $this->totalUnsettled += ($latestUnsettled ?? 0);
        }

        // ---------------------------------------------------------
        // 4. Expenses (Strict Range Sum)
        // ---------------------------------------------------------
        $this->monthlyExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
                                ->sum('amount');
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}