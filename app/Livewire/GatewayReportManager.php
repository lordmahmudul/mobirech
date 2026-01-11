<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GatewayProvider;
use App\Models\GatewayDailyReport;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GatewayReportManager extends Component
{
    use WithPagination;

    public $report_id, $gateway_provider_id, $report_date, $amount_collected, $amount_settled, $amount_unsettled;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.gateway-report-manager', [
            'reports' => GatewayDailyReport::with('provider')->latest('report_date')->paginate(10),
            'providers' => GatewayProvider::where('is_active', true)->get(),
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
        $this->gateway_provider_id = '';
        $this->report_date = '';
        $this->amount_collected = '';
        $this->amount_settled = '';
        $this->amount_unsettled = '';
    }

    public function store()
    {
        $this->validate([
            'gateway_provider_id' => 'required|exists:gateway_providers,id',
            'amount_collected' => 'required|numeric',
            'amount_settled' => 'required|numeric',
            'amount_unsettled' => 'required|numeric',
            'report_date' => [
                'required', 
                'date',
                Rule::unique('gateway_daily_reports')->where(function ($query) {
                    return $query->where('gateway_provider_id', $this->gateway_provider_id);
                })->ignore($this->report_id)
            ],
        ]);

        GatewayDailyReport::updateOrCreate(['id' => $this->report_id], [
            'gateway_provider_id' => $this->gateway_provider_id,
            'report_date' => $this->report_date,
            'amount_collected' => $this->amount_collected,
            'amount_settled' => $this->amount_settled,
            'amount_unsettled' => $this->amount_unsettled,
        ]);

        session()->flash('message', $this->report_id ? 'Report Updated.' : 'Report Created.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $report = GatewayDailyReport::findOrFail($id);
        $this->report_id = $id;
        $this->gateway_provider_id = $report->gateway_provider_id;
        $this->report_date = $report->report_date->format('Y-m-d');
        $this->amount_collected = $report->amount_collected;
        $this->amount_settled = $report->amount_settled;
        $this->amount_unsettled = $report->amount_unsettled;
        $this->openModal();
    }

    public function delete($id)
    {
        GatewayDailyReport::find($id)->delete();
        session()->flash('message', 'Report Deleted.');
    }
}