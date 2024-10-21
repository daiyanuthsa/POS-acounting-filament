<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle event "creating" pada model Product.
     * 
     * Method ini dipanggil sebelum sebuah Product baru disimpan ke database.
     * Ia membuat Account baru yang unik untuk produk tersebut.
     *
     * @param  Product  $product
     * @return void
     */
    public function creating(Product $product)
    {
        $account = $this->createUniqueAccount($product);
        $product->account_id = $account->id;
    }

    /**
     * Handle event "updating" pada model Product.
     * 
     * Method ini dipanggil ketika sebuah Product diperbarui.
     * Ia memperbarui Account yang terkait dengan produk tersebut.
     *
     * @param  Product  $product
     * @return void
     */
    public function updating(Product $product)
    {
        $this->updateAssociatedAccount($product);
    }

    /**
     * Buat Account unik untuk sebuah Product.
     * 
     * Method ini membuat Account baru dengan kode unik untuk setiap produk.
     *
     * @param  Product  $product
     * @return Account
     */
    private function createUniqueAccount(Product $product)
    {
        $lastAccount = Account::where('code', 'like', '4-%')
            ->where('team_id', $product->team_id)
            ->orderBy('code', 'desc')
            ->first();

        $newCode = $lastAccount
            ? '4-' . (intval(substr($lastAccount->code, 2)) + 1)
            : '4-1';

        $account = new Account();
        $account->code = $newCode;
        $account->accountName = 'Pendapatan ' . $product->name;
        $account->accountType = 'Revenue';
        $account->team_id = $product->team_id;
        $account->user_id = $product->user_id;
        $account->save();

        return $account;
    }

    /**
     * Perbarui Account yang terkait dengan sebuah Product.
     * 
     * Method ini memperbarui informasi Account yang terkait dengan produk
     * ketika produk tersebut diupdate.
     *
     * @param  Product  $product
     * @return void
     */
    private function updateAssociatedAccount(Product $product)
    {
        $account = $product->account;

        if ($account) {
            $account->accountName = 'Pendapatan ' . $product->name;
            $account->team_id = $product->team_id;
            $account->user_id = $product->user_id;
            $account->save();
        } else {
            // Jika karena suatu alasan produk tidak memiliki akun, buat yang baru
            $newAccount = $this->createUniqueAccount($product);
            $product->account_id = $newAccount->id;
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
