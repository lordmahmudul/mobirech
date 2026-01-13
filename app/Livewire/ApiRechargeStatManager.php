<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ApiProvider;
use App\Models\ApiRechargeStat;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class ApiRechargeStatManager extends Component
{
    use WithPagination;

    public $stat_id, $api_provider_id, $report_date;
    public $api_success_amount, $api_success_count, $db_success_amount, $db_success_count;
    public $isModalOpen = false;

    // Filters
    public $dateFilter = 'yesterday';
    public $filterProvider;

    public function render()
    {
        // 1. Base Query
        $query = ApiRechargeStat::with('provider')->orderBy('report_date', 'desc');

        // 2. Date Logic
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
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $query->whereBetween('report_date', [$startDate, $endDate]);
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                $query->whereBetween('report_date', [$startDate, $endDate]);
                break;
            case 'all':
                // No date filter
                break;
        }

        // 3. Provider Filter
        if ($this->filterProvider) {
            $query->where('api_provider_id', $this->filterProvider);
        }

        // 4. Calculate Totals
        // Clone the query to calculate totals based on current filters
        $totalApiAmount = (clone $query)->sum('api_success_amount');
        $totalApiCount = (clone $query)->sum('api_success_count');
        $totalDbAmount = (clone $query)->sum('db_success_amount');
        $totalDbCount = (clone $query)->sum('db_success_count');

        // Calculate Differences
        $totalDiffAmount = $totalApiAmount - $totalDbAmount;
        $totalDiffCount = $totalApiCount - $totalDbCount;

        return view('livewire.api-recharge-stat-manager', [
            'stats' => $query->paginate(10),
            'providers' => ApiProvider::all(),
            'totalApiAmount' => $totalApiAmount,
            'totalApiCount' => $totalApiCount,
            'totalDbAmount' => $totalDbAmount,
            'totalDbCount' => $totalDbCount,
            'totalDiffAmount' => $totalDiffAmount, // Passed to view
            'totalDiffCount' => $totalDiffCount,   // Passed to view
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
        $this->stat_id = null;
        $this->api_provider_id = '';
        $this->report_date = Carbon::yesterday()->format('Y-m-d');
        $this->api_success_amount = '';
        $this->api_success_count = '';
        $this->db_success_amount = '';
        $this->db_success_count = '';
    }

    public function store()
    {
        $this->validate([
            'api_provider_id' => 'required|exists:api_providers,id',
            'api_success_amount' => 'required|numeric',
            'api_success_count' => 'required|integer',
            'db_success_amount' => 'required|numeric',
            'db_success_count' => 'required|integer',
            'report_date' => [
                'required', 
                'date',
                'before_or_equal:today',
                Rule::unique('api_recharge_stats')->where(function ($query) {
                    return $query->where('api_provider_id', $this->api_provider_id);
                })->ignore($this->stat_id)
            ],
        ]);

        ApiRechargeStat::updateOrCreate(['id' => $this->stat_id], [
            'api_provider_id' => $this->api_provider_id,
            'report_date' => $this->report_date,
            'api_success_amount' => $this->api_success_amount,
            'api_success_count' => $this->api_success_count,
            'db_success_amount' => $this->db_success_amount,
            'db_success_count' => $this->db_success_count,
        ]);

        session()->flash('message', $this->stat_id ? 'Stats Updated.' : 'Stats Created.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $stat = ApiRechargeStat::findOrFail($id);
        $this->stat_id = $id;
        $this->api_provider_id = $stat->api_provider_id;
        $this->report_date = $stat->report_date->format('Y-m-d');
        $this->api_success_amount = $stat->api_success_amount;
        $this->api_success_count = $stat->api_success_count;
        $this->db_success_amount = $stat->db_success_amount;
        $this->db_success_count = $stat->db_success_count;
        $this->openModal();
    }

    public function delete($id)
    {
        ApiRechargeStat::find($id)->delete();
        session()->flash('message', 'Stats Deleted.');
    }
}