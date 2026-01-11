<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bank;

class BankManager extends Component
{
    public $banks, $bank_name, $account_number, $bank_id;
    public $isModalOpen = false;

    // Validation Rules
    protected $rules = [
        'bank_name' => 'required|string|max:255',
        'account_number' => 'nullable|string|max:50',
    ];

    public function render()
    {
        $this->banks = Bank::latest()->get();
        return view('livewire.bank-manager')->layout('layouts.app');
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
    }

    private function resetInputFields()
    {
        $this->bank_name = '';
        $this->account_number = '';
        $this->bank_id = '';
    }

    public function store()
    {
        $this->validate();

        Bank::updateOrCreate(['id' => $this->bank_id], [
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
        ]);

        session()->flash('message', $this->bank_id ? 'Bank Updated Successfully.' : 'Bank Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        $this->bank_id = $id;
        $this->bank_name = $bank->bank_name;
        $this->account_number = $bank->account_number;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Bank::find($id)->delete();
        session()->flash('message', 'Bank Deleted Successfully.');
    }
}