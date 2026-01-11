<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BankManager;
use App\Livewire\DailyBalanceManager;
use App\Livewire\ApiProviderManager;
use App\Livewire\ApiReportManager;
use App\Livewire\GatewayProviderManager;
use App\Livewire\GatewayReportManager;
use App\Livewire\ExpenseCategoryManager;
use App\Livewire\ExpenseManager;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    Route::get('/banks', BankManager::class)->name('banks');
    Route::get('/daily-balances', DailyBalanceManager::class)->name('daily-balances');
    Route::get('/api-providers', ApiProviderManager::class)->name('api-providers');
    Route::get('/api-reports', ApiReportManager::class)->name('api-reports');
    Route::get('/gateways', GatewayProviderManager::class)->name('gateways');
    Route::get('/gateway-reports', GatewayReportManager::class)->name('gateway-reports');
    Route::get('/expense-categories', ExpenseCategoryManager::class)->name('expense-categories');
    Route::get('/expenses', ExpenseManager::class)->name('expenses');

});
