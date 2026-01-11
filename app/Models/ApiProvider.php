<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiProvider extends Model
{
    protected $fillable = ['provider_name', 'current_balance'];

    public function dailyReports(): HasMany
    {
        return $this->hasMany(ApiProviderDailyReport::class);
    }
}