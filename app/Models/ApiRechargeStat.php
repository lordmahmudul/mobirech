<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRechargeStat extends Model
{
    protected $fillable = [
        'api_provider_id',
        'report_date',
        'api_success_amount',
        'api_success_count',
        'db_success_amount',
        'db_success_count',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id');
    }
}