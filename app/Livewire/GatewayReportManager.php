<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GatewayProvider;
use App\Models\GatewayDailyReport;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class GatewayReportManager extends Component
{
    use WithPagination;

    public $report_id, $gateway_provider_id, $report_date, $amount_collected, $amount_settled, $amount_unsettled;
    public $isModalOpen = false;

    // Filters
    public $dateFilter = 'yesterday'; // Default
    public $filterGateway;

public function render()
    {
        // 1. Base Query
        $query = GatewayDailyReport::with('provider')->orderBy('report_date', 'desc');

        // 2. Define Strict Date Boundaries
        $startDate = null;
        $endDate = null;
        
        switch ($this->dateFilter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                $query->whereDate('report_date', Carbon::today());
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                $query->whereDate('report_date', Carbon::yesterday());
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $query->whereBetween('report_date', [$startDate, $endDate]);
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                $query->whereBetween('report_date', [$startDate, $endDate]);
                break;
            case 'all':
                // No strict boundaries for "All Time"
                break;
        }

        // 3. Gateway Filter
        if ($this->filterGateway) {
            $query->where('gateway_provider_id', $this->filterGateway);
        }

        // 4. Calculate Totals
        // Collected & Settled are straightforward sums of the period
        $totalCollected = (clone $query)->sum('amount_collected');
        $totalSettled = (clone $query)->sum('amount_settled');

        // Unsettled: STRICTLY find the latest record within the selected start/end dates
        $totalUnsettled = 0;
        $gatewayIds = $this->filterGateway ? [$this->filterGateway] : GatewayProvider::pluck('id')->toArray();

        foreach ($gatewayIds as $id) {
            // Start query for this gateway
            $balanceQuery = GatewayDailyReport::where('gateway_provider_id', $id);

            // Apply Strict Date Filters
            if ($this->dateFilter !== 'all' && $startDate && $endDate) {
                // Must be found INSIDE the date range
                $balanceQuery->whereBetween('report_date', [$startDate, $endDate]);
            } else {
                // For 'all', just get the latest one known
                $balanceQuery->where('report_date', '<=', Carbon::now());
            }

            $latestUnsettled = $balanceQuery->orderBy('report_date', 'desc')
                                            ->value('amount_unsettled');
            
            $totalUnsettled += ($latestUnsettled ?? 0);
        }

        return view('livewire.gateway-report-manager', [
            'reports' => $query->paginate(10),
            'providers' => GatewayProvider::where('is_active', true)->get(),
            'totalCollected' => $totalCollected,
            'totalSettled' => $totalSettled,
            'totalUnsettled' => $totalUnsettled,
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
        $this->report_date = Carbon::yesterday()->format('Y-m-d');
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
                'before_or_equal:today', // Prevent future dates
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