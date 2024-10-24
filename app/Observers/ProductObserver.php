<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Product;

class ProductObserver
{

    /**
     * Handle event "creating" pada model Product.
     *
     * @param  Product  $product
     * @return void
     */
    public function creating(Product $product)
    {
        // Buat akun pendapatan dan set sebagai parent
        $revenueAccount = $this->createRevenueAccount($product);
        $product->account_id = $revenueAccount->id;

        // Buat akun persediaan dan HPP sebagai child dari akun pendapatan
        $this->createInventoryAccount($product, $revenueAccount);
        $this->createCOGAccount($product, $revenueAccount);
    }

    /**
     * Handle event "updating" pada model Product.
     *
     * @param  Product  $product
     * @return void
     */
    public function updating(Product $product)
    {
        $this->updateAssociatedAccounts($product);
    }

    /**
     * Buat Account Pendapatan untuk sebuah Product.
     *
     * @param  Product  $product
     * @return Account
     */
    private function createRevenueAccount(Product $product)
    {
        $lastAccount = Account::where('code', 'like', '4-%')
            ->where('team_id', $product->team_id)
            ->whereNull('parent_id')
            ->orderBy('code', 'desc')
            ->first();

        $newCode = $lastAccount
            ? '4-' . (intval(substr($lastAccount->code, 2)) + 1)
            : '4-1';

        return Account::create([
            'code' => $newCode,
            'accountName' => 'Pendapatan ' . $product->name,
            'accountType' => 'Revenue',
            'team_id' => auth()->user()->teams()->first()->id,
            'user_id' => $product->user_id,
        ]);
    }

    /**
     * Buat Account Persediaan sebagai child dari akun pendapatan.
     *
     * @param  Product  $product
     * @param  Account  $parentAccount
     * @return Account
     */
    private function createInventoryAccount(Product $product, Account $parentAccount)
    {
        $lastAccount = Account::where('code', 'like', '1-14%')
            ->where('team_id', $product->team_id)
            ->orderBy('code', 'desc')
            ->first();

        $newCode = $lastAccount
            ? '1-14' . (intval(substr($lastAccount->code, 6)) + 1)
            : '1-141';

        return Account::create([
            'code' => $newCode,
            'accountName' => 'Persediaan ' . $product->name,
            'accountType' => 'Asset',
            'asset_type' => 'current',
            'team_id' => auth()->user()->teams()->first()->id,
            'user_id' => $product->user_id,
            'parent_id' => $parentAccount->id,
        ]);
    }

    /**
     * Buat Account HPP sebagai child dari akun pendapatan.
     *
     * @param  Product  $product
     * @param  Account  $parentAccount
     * @return Account
     */
    private function createCOGAccount(Product $product, Account $parentAccount)
    {
        $lastAccount = Account::where('code', 'like', '5-11%')
            ->where('team_id', $product->team_id)
            ->orderBy('code', 'desc')
            ->first();

        $newCode = $lastAccount
            ? '5-11' . (intval(substr($lastAccount->code, 6)) + 1)
            : '5-111';

        return Account::create([
            'code' => $newCode,
            'accountName' => 'HPP ' . $product->name,
            'accountType' => 'UPC',
            'team_id' => auth()->user()->teams()->first()->id,
            'user_id' => $product->user_id,
            'parent_id' => $parentAccount->id,
        ]);
    }

    /**
     * Perbarui Account-account yang terkait dengan sebuah Product.
     *
     * @param  Product  $product
     * @return void
     */
    private function updateAssociatedAccounts(Product $product)
    {
        if ($product->account) {
            // Update parent account (Pendapatan)
            $product->account->update([
                'accountName' => 'Pendapatan ' . $product->name,
                'team_id' => $product->team_id,
                'user_id' => $product->user_id,
            ]);

            // Update child accounts
            foreach ($product->account->children as $childAccount) {
                $prefix = '';
                if (str_starts_with($childAccount->code, '1-14')) {
                    $prefix = 'Persediaan ';
                } elseif (str_starts_with($childAccount->code, '5-11')) {
                    $prefix = 'HPP ';
                }

                if ($prefix) {
                    $childAccount->update([
                        'accountName' => $prefix . $product->name,
                        'team_id' => $product->team_id,
                        'user_id' => $product->user_id,
                    ]);
                }
            }
        } else {
            // Jika tidak ada akun, buat baru
            $revenueAccount = $this->createRevenueAccount($product);
            $product->account_id = $revenueAccount->id;
            $this->createInventoryAccount($product, $revenueAccount);
            $this->createCOGAccount($product, $revenueAccount);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
