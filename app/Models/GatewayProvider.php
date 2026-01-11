<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GatewayProvider extends Model
{
    protected $fillable = ['provider_name', 'is_active'];

    public function dailyReports(): HasMany
    {
        return $this->hasMany(GatewayDailyReport::class);
    }
}