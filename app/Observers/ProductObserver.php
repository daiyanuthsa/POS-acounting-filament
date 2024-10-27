<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product)
    {
        $revenueAccount = $this->createRevenueAccount($product);
        $product->account_id = $revenueAccount->id;

        $inventoryAccount = $this->createInventoryAccount($product);
        $product->stock_id = $inventoryAccount->id;

        $upcAccount = $this->createCOGAccount($product);
        $product->upc_id = $upcAccount->id;
    }

    public function updating(Product $product)
    {
        $this->updateAssociatedAccounts($product);
    }

    private function createRevenueAccount(Product $product)
    {
        $lastAccount = Account::where('code', 'like', '4-%')
            ->where('team_id', $product->team_id)
            ->orderBy('code', 'desc')
            ->first();

        $newCode = $lastAccount
            ? '4-' . str_pad((intval(substr($lastAccount->code, 2)) + 1), 3, '0', STR_PAD_LEFT)
            : '4-100';


        return Account::create([
            'code' => $newCode,
            'accountName' => 'Pendapatan ' . $product->name,
            'accountType' => 'Revenue',
            'team_id' => $product->team_id,
            'user_id' => $product->user_id,
        ]);
    }

    private function createInventoryAccount(Product $product)
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
            'team_id' => $product->team_id,
            'user_id' => $product->user_id,
        ]);
    }

    private function createCOGAccount(Product $product)
    {
        $lastAccount = Account::where('code', 'like', '5-11%')
            ->where('team_id', $product->team_id)
            ->orderBy('code', 'desc')
            ->first();

        $newCode = $lastAccount
            ? '5-' . str_pad((intval(substr($lastAccount->code, 2)) + 1), 3, '0', STR_PAD_LEFT)
            : '5-100';


        return Account::create([
            'code' => $newCode,
            'accountName' => 'HPP ' . $product->name,
            'accountType' => 'UPC',
            'team_id' => $product->team_id,
            'user_id' => $product->user_id,
        ]);
    }

    private function updateAssociatedAccounts(Product $product)
    {
        if ($product->account) {
            $product->account->update([
                'accountName' => 'Pendapatan ' . $product->name,
                'team_id' => $product->team_id,
                'user_id' => $product->user_id,
            ]);

            if ($product->stock_id) {
                $product->stockAccount->update([
                    'accountName' => 'Persediaan ' . $product->name,
                    'team_id' => $product->team_id,
                    'user_id' => $product->user_id,
                ]);
            }

            if ($product->upc_id) {
                $product->upcAccount->update([
                    'accountName' => 'HPP ' . $product->name,
                    'team_id' => $product->team_id,
                    'user_id' => $product->user_id,
                ]);
            }
        } else {
            $revenueAccount = $this->createRevenueAccount($product);
            $product->account_id = $revenueAccount->id;

            $inventoryAccount = $this->createInventoryAccount($product);
            $product->stock_id = $inventoryAccount->id;

            $upcAccount = $this->createCOGAccount($product);
            $product->upc_id = $upcAccount->id;
        }
    }

    public function deleted(Product $product): void
    {
        //
    }

    public function restored(Product $product): void
    {
        //
    }

    public function forceDeleted(Product $product): void
    {
        //
    }
}
