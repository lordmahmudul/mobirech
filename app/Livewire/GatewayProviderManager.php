<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GatewayProvider;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GatewayProviderManager extends Component
{
    public $providers, $provider_name, $is_active, $provider_id;
    public $isModalOpen = false;

    protected $rules = [
        'provider_name' => 'required|string|max:255',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $this->providers = GatewayProvider::all();
        return view('livewire.gateway-provider-manager');
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
        $this->is_active = true;
        $this->provider_id = null;
    }

    public function store()
    {
        $this->validate();

        GatewayProvider::updateOrCreate(['id' => $this->provider_id], [
            'provider_name' => $this->provider_name,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->provider_id ? 'Gateway Updated.' : 'Gateway Created.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $provider = GatewayProvider::findOrFail($id);
        $this->provider_id = $id;
        $this->provider_name = $provider->provider_name;
        $this->is_active = $provider->is_active;
        $this->openModal();
    }

    public function delete($id)
    {
        GatewayProvider::find($id)->delete();
        session()->flash('message', 'Gateway Deleted.');
    }
}