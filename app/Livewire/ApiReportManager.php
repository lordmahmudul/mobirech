<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ApiProvider;
use App\Models\ApiProviderDailyReport;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ApiReportManager extends Component
{
    use WithPagination;

    public $report_id, $api_provider_id, $report_date, $balance_added, $balance_used, $available_balance;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.api-report-manager', [
            'reports' => ApiProviderDailyReport::with('provider')->latest('report_date')->paginate(10),
            'providers' => ApiProvider::all(),
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
        $this->report_id = null;
        $this->api_provider_id = '';
        $this->report_date = '';
        $this->balance_added = '';
        $this->balance_used = '';
        $this->available_balance = '';
    }

    public function store()
    {
        $this->validate([
            'api_provider_id' => 'required|exists:api_providers,id',
            'balance_added' => 'required|numeric',
            'balance_used' => 'required|numeric',
            'available_balance' => 'required|numeric',
            'report_date' => [
                'required', 
                'date',
                Rule::unique('api_provider_daily_reports')->where(function ($query) {
                    return $query->where('api_provider_id', $this->api_provider_id);
                })->ignore($this->report_id)
            ],
        ]);

        ApiProviderDailyReport::updateOrCreate(['id' => $this->report_id], [
            'api_provider_id' => $this->api_provider_id,
            'report_date' => $this->report_date,
            'balance_added' => $this->balance_added,
            'balance_used' => $this->balance_used,
            'available_balance' => $this->available_balance,
        ]);

        session()->flash('message', $this->report_id ? 'Report Updated.' : 'Report Created.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $report = ApiProviderDailyReport::findOrFail($id);
        $this->report_id = $id;
        $this->api_provider_id = $report->api_provider_id;
        $this->report_date = $report->report_date->format('Y-m-d');
        $this->balance_added = $report->balance_added;
        $this->balance_used = $report->balance_used;
        $this->available_balance = $report->available_balance;
        $this->openModal();
    }

    public function delete($id)
    {
        ApiProviderDailyReport::find($id)->delete();
        session()->flash('message', 'Report Deleted.');
    }
}