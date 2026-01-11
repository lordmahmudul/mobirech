<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankDailyBalance extends Model
{
    protected $fillable = [
        'bank_id',
        'balance_date',
        'available_balance',
    ];

    protected $casts = [
        'balance_date' => 'date',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
