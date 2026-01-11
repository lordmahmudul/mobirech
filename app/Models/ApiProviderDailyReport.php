<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiProviderDailyReport extends Model
{
    protected $fillable = [
        'api_provider_id', 
        'report_date', 
        'balance_added', 
        'balance_used', 
        'available_balance'
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id');
    }
}