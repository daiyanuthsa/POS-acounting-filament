<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\CashFlow;

class CashFlowObserver
{
    /**
     * Handle the CashFlow "created" event.
     */
    // public function created(CashFlow $cashFlow): void
    // {
    //     // Mengambil kode akun yang terkait
    //     $accountCode = $cashFlow->account->code;
    //     $cashId = Account::where('code', 'like', '1-11')
    //         ->where('team_id', $cashFlow->team_id) ;

    //     // Jika akun revenue (pendapatan) yang diawali dengan 4-
    //     if (strpos($accountCode, '4-') === 0) {
    //         // Buat entri cash flow untuk pendapatan, tambahkan ke kas (1-11)
    //         CashFlow::create([
    //             'team_id'=> $cashFlow->team_id,
    //             'user_id' => $cashFlow->user_id,
    //             'account_id' => $cashId, 
    //             'amount' => $cashFlow->amount,
    //             'type' => 'debit', // Pendapatan masuk sebagai debit ke kas
    //         ]);
    //     }

    //     // Jika transaksi melibatkan akun asset (yang diawali dengan 1-)
    //     elseif (strpos($accountCode, '1-') === 0) {
    //         CashFlow::create([
    //             'team_id' => $cashFlow->team_id,
    //             'user_id' => $cashFlow->user_id,
    //             'account_id' => $cashId,
    //             'amount' => $cashFlow->amount,
    //             'type' => 'debit', // Aset biasanya bertambah sebagai debit
    //         ]);
    //     }

    //     // Jika transaksi melibatkan akun liability (yang diawali dengan 2-)
    //     elseif (strpos($accountCode, '3-') === 0
    //     || strpos($accountCode, '2-') === 0) {
    //         CashFlow::create([
    //             'team_id' => $cashFlow->team_id,
    //             'user_id' => $cashFlow->user_id,
    //             'account_id' => $cashId,
    //             'amount' => $cashFlow->amount,
    //             'type' => 'credit', // Kewajiban bertambah sebagai kredit
    //         ]);
    //     }

    //     // Jika transaksi melibatkan akun equity (yang diawali dengan 3-)
    //     // elseif (strpos($accountCode, '2-') === 0) {
    //     //     CashFlow::create([
    //     //         'team_id' => $cashFlow->team_id,
    //     //         'user_id' => $cashFlow->user_id,
    //     //         'account_id' => $cashFlow->account_id,
    //     //         'amount' => $cashFlow->amount,
    //     //         'type' => 'credit', // Ekuitas bertambah sebagai kredit
    //     //     ]);
    //     // }

    //     // Jika transaksi melibatkan akun expense (yang diawali dengan 5-)
    //     elseif (strpos($accountCode, '5-') === 0) {
    //         CashFlow::create([
    //             'team_id' => $cashFlow->team_id,
    //             'user_id' => $cashFlow->user_id,
    //             'account_id' => $cashId,
    //             'amount' => $cashFlow->amount,
    //             'type' => 'debit', // Biaya bertambah sebagai debit
    //         ]);
    //     }

    // }

    /**
     * Handle the CashFlow "updated" event.
     */
    public function updated(CashFlow $cashFlow): void
    {
        //
    }

    /**
     * Handle the CashFlow "deleted" event.
     */
    public function deleted(CashFlow $cashFlow): void
    {
        //
    }

    /**
     * Handle the CashFlow "restored" event.
     */
    public function restored(CashFlow $cashFlow): void
    {
        //
    }

    /**
     * Handle the CashFlow "force deleted" event.
     */
    public function forceDeleted(CashFlow $cashFlow): void
    {
        //
    }
}
