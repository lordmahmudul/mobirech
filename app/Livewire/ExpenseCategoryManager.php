<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExpenseCategory;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ExpenseCategoryManager extends Component
{
    public $categories, $name, $description, $category_id;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $this->categories = ExpenseCategory::all();
        return view('livewire.expense-category-manager');
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
        $this->name = '';
        $this->description = '';
        $this->category_id = null;
    }

    public function store()
    {
        $this->validate();

        ExpenseCategory::updateOrCreate(['id' => $this->category_id], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', $this->category_id ? 'Category Updated.' : 'Category Created.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->openModal();
    }

    public function delete($id)
    {
        ExpenseCategory::find($id)->delete();
        session()->flash('message', 'Category Deleted.');
    }
}