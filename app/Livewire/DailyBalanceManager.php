<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bank;
use App\Models\BankDailyBalance;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class DailyBalanceManager extends Component
{
    use WithPagination;

    public $balance_id, $bank_id, $balance_date, $available_balance;
    public $isModalOpen = false;

    // Filters
    public $dateFilter = 'yesterday'; // Default as requested
    public $filterBank;

    public function render()
    {
        // 1. Table Query (Keeps showing only records for the specific range so you know what was actually entered)
        $query = BankDailyBalance::with('bank')->orderBy('balance_date', 'desc');

        // 2. Determine "Cutoff Date" for the Total Calculation
        // This date represents "The end of the period we are looking at"
        $cutoffDate = Carbon::now(); 
        
        // Start/End for the Table Filter
        $tableStartDate = null;
        $tableEndDate = null;

        switch ($this->dateFilter) {
            case 'today':
                $tableStartDate = Carbon::today();
                $tableEndDate = Carbon::today();
                $cutoffDate = Carbon::today();
                $query->whereDate('balance_date', Carbon::today());
                break;
            case 'yesterday':
                $tableStartDate = Carbon::yesterday();
                $tableEndDate = Carbon::yesterday();
                $cutoffDate = Carbon::yesterday();
                $query->whereDate('balance_date', Carbon::yesterday());
                break;
            case 'this_week':
                $tableStartDate = Carbon::now()->startOfWeek();
                $tableEndDate = Carbon::now()->endOfWeek();
                $cutoffDate = Carbon::now()->endOfWeek(); // Latest possible in this week
                $query->whereBetween('balance_date', [$tableStartDate, $tableEndDate]);
                break;
            case 'last_month':
                $tableStartDate = Carbon::now()->subMonth()->startOfMonth();
                $tableEndDate = Carbon::now()->subMonth()->endOfMonth();
                $cutoffDate = Carbon::now()->subMonth()->endOfMonth(); // End of last month
                $query->whereBetween('balance_date', [$tableStartDate, $tableEndDate]);
                break;
            case 'all':
                $cutoffDate = Carbon::now(); // Current live balance
                // No table filter
                break;
        }

        // 3. Apply Bank Filter to Table
        if ($this->filterBank) {
            $query->where('bank_id', $this->filterBank);
        }

        // 4. Calculate Correct "Balance As Of" Total
        // Logic: For each bank, find the latest record ON or BEFORE the cutoff date.
        $totalAmount = 0;
        
        $bankIds = $this->filterBank ? [$this->filterBank] : Bank::pluck('id')->toArray();

        foreach ($bankIds as $bankId) {
            // Find the most recent record for this bank prior to (or on) the cutoff date
            $latestBalance = BankDailyBalance::where('bank_id', $bankId)
                                ->where('balance_date', '<=', $cutoffDate)
                                ->orderBy('balance_date', 'desc')
                                ->value('available_balance');
            
            // If found, add to total. If never found (new bank with no history), add 0.
            $totalAmount += ($latestBalance ?? 0);
        }

        return view('livewire.daily-balance-manager', [
            'dailyBalances' => $query->paginate(10),
            'banks' => Bank::all(),
            'totalAmount' => $totalAmount,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->balance_id = null;
        $this->bank_id = '';
        // Default the input date to Yesterday for convenience, or Today
        $this->balance_date = Carbon::yesterday()->format('Y-m-d'); 
        $this->available_balance = '';
    }

    public function store()
    {
        $this->validate([
            'bank_id' => 'required|exists:banks,id',
            'available_balance' => 'required|numeric',
            'balance_date' => [
                'required', 
                'date',
                'before_or_equal:today', // <--- PREVENT FUTURE DATES
                Rule::unique('bank_daily_balances')->where(function ($query) {
                    return $query->where('bank_id', $this->bank_id);
                })->ignore($this->balance_id)
            ],
        ], [
            'balance_date.before_or_equal' => 'You cannot add a balance for a future date.'
        ]);

        BankDailyBalance::updateOrCreate(['id' => $this->balance_id], [
            'bank_id' => $this->bank_id,
            'balance_date' => $this->balance_date,
            'available_balance' => $this->available_balance,
        ]);

        session()->flash('message', $this->balance_id ? 'Record Updated Successfully.' : 'Record Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $record = BankDailyBalance::findOrFail($id);
        
        $this->balance_id = $id;
        $this->bank_id = $record->bank_id;
        $this->balance_date = $record->balance_date->format('Y-m-d');
        $this->available_balance = $record->available_balance;
    
        $this->openModal();
    }

    public function delete($id)
    {
        BankDailyBalance::find($id)->delete();
        session()->flash('message', 'Record Deleted Successfully.');
    }
}