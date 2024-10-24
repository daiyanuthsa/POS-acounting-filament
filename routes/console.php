<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Artisan::command('transaction:run', function () {
//     $controller = new TransactionController();
//     $controller->processUnrecordedOrders(); // Ganti dengan method yang ingin dieksekusi
//     $this->info('TransactionController has been executed');
// })->everyMinute();