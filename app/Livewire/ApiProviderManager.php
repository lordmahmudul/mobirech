<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ApiProvider;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ApiProviderManager extends Component
{
    public $providers, $provider_name, $current_balance, $provider_id;
    public $isModalOpen = false;

    protected $rules = [
        'provider_name' => 'required|string|max:255',
        'current_balance' => 'required|numeric|min:0',
    ];

    public function render()
    {
        $this->providers = ApiProvider::all();
        return view('livewire.api-provider-manager');
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
        $this->provider_name = '';
        $this->current_balance = '';
        $this->provider_id = '';
    }

    public function store()
    {
        $this->validate();

        ApiProvider::updateOrCreate(['id' => $this->provider_id], [
            'provider_name' => $this->provider_name,
            'current_balance' => $this->current_balance,
        ]);

        session()->flash('message', $this->provider_id ? 'Provider Updated.' : 'Provider Created.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $provider = ApiProvider::findOrFail($id);
        $this->provider_id = $id;
        $this->provider_name = $provider->provider_name;
        $this->current_balance = $provider->current_balance;
        $this->openModal();
    }

    public function delete($id)
    {
        ApiProvider::find($id)->delete();
        session()->flash('message', 'Provider Deleted.');
    }
}