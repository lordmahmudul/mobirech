<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayDailyReport extends Model
{
    protected $fillable = [
        'gateway_provider_id', 
        'report_date', 
        'amount_collected', 
        'amount_settled', 
        'amount_unsettled'
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(GatewayProvider::class, 'gateway_provider_id');
    }
}