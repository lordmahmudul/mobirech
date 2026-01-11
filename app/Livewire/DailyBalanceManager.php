<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bank;
use App\Models\BankDailyBalance;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')] // <--- FIX: This prevents the MissingLayoutException
class DailyBalanceManager extends Component
{
    use WithPagination;

    public $balance_id, $bank_id, $balance_date, $available_balance;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.daily-balance-manager', [
            // Get balances with the associated Bank Name
            'dailyBalances' => BankDailyBalance::with('bank')
                                ->orderBy('balance_date', 'desc')
                                ->paginate(10),
            
            // Get list of banks for the Dropdown menu
            'banks' => Bank::all(),
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
        $this->resetValidation(); // Clear red error text
    }

    private function resetInputFields()
    {
        $this->balance_id = null;
        $this->bank_id = '';
        $this->balance_date = '';
        $this->available_balance = '';
    }

    public function store()
    {
        // Validation: Ensure (Bank + Date) combination is unique, unless we are editing the same record
        $this->validate([
            'bank_id' => 'required|exists:banks,id',
            'available_balance' => 'required|numeric',
            'balance_date' => [
                'required', 
                'date',
                Rule::unique('bank_daily_balances')->where(function ($query) {
                    return $query->where('bank_id', $this->bank_id);
                })->ignore($this->balance_id)
            ],
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
        // Format date for the HTML input (Y-m-d)
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