<?php

use App\Http\Controllers\Report\BalanceSheet;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
});
Route::get('/profit-loss', function () {
    return inertia('ProfitLoss');
});
Route::get('/financial-position', function () {
    return inertia('FinancialPosition');
});
Route::get('/changes-equity', function () {
    return inertia('ChangesInEquity');
});


Route::get('/report-balancesheet', [BalanceSheet::class, 'index']);

Route::get('/update', [TransactionController::class, 'processUnrecordedOrders']);
