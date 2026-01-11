<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Bank;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ExpenseManager extends Component
{
    use WithPagination;

    public $expense_id, $expense_category_id, $bank_id, $expense_date, $amount, $description, $reference_no;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.expense-manager', [
            'expenses' => Expense::with(['category', 'bank'])->latest('expense_date')->paginate(10),
            'categories' => ExpenseCategory::where('is_active', true)->get(),
            'banks' => Bank::all(), // Fetch banks so we can pay from an account
        ]);
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
        $this->expense_id = null;
        $this->expense_category_id = '';
        $this->bank_id = '';
        $this->expense_date = date('Y-m-d'); // Default to today
        $this->amount = '';
        $this->description = '';
        $this->reference_no = '';
    }

    public function store()
    {
        $this->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'bank_id' => 'nullable|exists:banks,id', // Optional
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'reference_no' => 'nullable|string|max:50',
        ]);

        Expense::updateOrCreate(['id' => $this->expense_id], [
            'expense_category_id' => $this->expense_category_id,
            'bank_id' => $this->bank_id ?: null, // Handle empty string
            'expense_date' => $this->expense_date,
            'amount' => $this->amount,
            'description' => $this->description,
            'reference_no' => $this->reference_no,
        ]);

        session()->flash('message', $this->expense_id ? 'Expense Updated.' : 'Expense Recorded.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expense_id = $id;
        $this->expense_category_id = $expense->expense_category_id;
        $this->bank_id = $expense->bank_id;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->amount = $expense->amount;
        $this->description = $expense->description;
        $this->reference_no = $expense->reference_no;
        $this->openModal();
    }

    public function delete($id)
    {
        Expense::find($id)->delete();
        session()->flash('message', 'Expense Deleted.');
    }
}