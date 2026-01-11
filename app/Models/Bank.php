<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bank extends Model
{
    protected $fillable = [
        'bank_name',
        'account_number',
    ];

    public function dailyBalances(): HasMany
    {
        return $this->hasMany(BankDailyBalance::class);
    }
    public function latestDailyBalance(): HasOne
    {
        return $this->hasOne(BankDailyBalance::class)->latest('balance_date');
    }
}
