<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApiProvider extends Model
{
    protected $fillable = ['provider_name', 'current_balance'];

    // Existing Daily Reports (Balance Added/Used)
    public function dailyReports(): HasMany
    {
        return $this->hasMany(ApiProviderDailyReport::class);
    }

    public function latestDailyReport(): HasOne
    {
        return $this->hasOne(ApiProviderDailyReport::class)->latest('report_date');
    }

    // NEW: Recharge Stats (Success Counts/Amounts)
    public function rechargeStats(): HasMany
    {
        return $this->hasMany(ApiRechargeStat::class);
    }

    // Helper to get latest stats
    public function latestRechargeStat(): HasOne
    {
        return $this->hasOne(ApiRechargeStat::class)->latest('report_date');
    }
}