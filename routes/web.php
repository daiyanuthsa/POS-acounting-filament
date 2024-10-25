<?php

use App\Http\Controllers\Report\BalanceSheet;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');

});

Route::get('/report', [BalanceSheet::class, 'index']);

Route::get('/update', [TransactionController::class, 'processUnrecordedOrders']);
