<?php

namespace App\Console\Commands;

use App\Http\Controllers\TransactionController;
use Illuminate\Console\Command;

class RunTransactionController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:run';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run TransactionController periodically';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new TransactionController();
        $controller->processUnrecordedOrders(); // Ganti dengan method yang ingin dieksekusi
        $this->info('TransactionController has been executed');
    }
}
