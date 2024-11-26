<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Team;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        // Buat akun wajib untuk tim baru
        $this->createDefaultAccounts($team);
    }

    /**
     * Buat akun-akun default yang wajib dimiliki setiap tim baru.
     *
     * @param  Team  $team
     * @return void
     */
    private function createDefaultAccounts(Team $team): void
    {
        $defaultAccounts = [
            [
                'code' => '1-110',
                'accountName' => 'Kas',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id, // Atau gunakan user default jika ada
                'asset_type' => 'current',
            ],
            [
                'code' => '1-120',
                'accountName' => 'Piutang',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'current',
            ],
            [
                'code' => '1-130',
                'accountName' => 'Perlengkapan',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'current',
            ],
            [
                'code' => '1-210',
                'accountName' => 'Tanah',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'fixed',
            ],
            [
                'code' => '1-220',
                'accountName' => 'Kendaraan',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'fixed',
            ],
            [
                'code' => '1-221',
                'accountName' => 'Akumulasi Penyusutan Kendaraan',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'fixed',
            ],
            [
                'code' => '1-230',
                'accountName' => 'Peralatan',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'fixed',
            ],
            [
                'code' => '1-231',
                'accountName' => 'Akumulasi Penyusutan Peralatan',
                'accountType' => 'Asset',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'asset_type' => 'fixed',
            ],
            // Liability
            [
                'code' => '2-110',
                'accountName' => 'Hutang Usaha',
                'accountType' => 'Liability',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            [
                'code' => '2-210',
                'accountName' => 'Hutang Bank',
                'accountType' => 'Liability',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            // Equity
            [
                'code' => '3-110',
                'accountName' => 'Modal Awal',
                'accountType' => 'Equity',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            [
                'code' => '3-210',
                'accountName' => 'Prive',
                'accountType' => 'Equity',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            [
                'code' => '3-310',
                'accountName' => 'Laba/Rugi Berjalan',
                'accountType' => 'Equity',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            // Beban
            [
                'code' => '6-110',
                'accountName' => 'Beban Gaji',
                'accountType' => 'Expense',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            [
                'code' => '6-111',
                'accountName' => 'Beban Penyusutan Kendaraan',
                'accountType' => 'Expense',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            [
                'code' => '6-112',
                'accountName' => 'Beban Penyusutan Peralatan',
                'accountType' => 'Expense',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            // Pendapatan Lain - Lain
            [
                'code' => '8-110',
                'accountName' => 'Pendapatan Bunga Bank',
                'accountType' => 'Revenue',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            [
                'code' => '8-111',
                'accountName' => 'Pendapatan Lain - Lain',
                'accountType' => 'Revenue',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
            // Beban Lain - Lain
            [
                'code' => '9-110',
                'accountName' => 'Beban Lain - Lain',
                'accountType' => 'Expense',
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
            ],
        ];

        foreach ($defaultAccounts as $accountData) {
            Account::create($accountData);
        }
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        //
    }
}
