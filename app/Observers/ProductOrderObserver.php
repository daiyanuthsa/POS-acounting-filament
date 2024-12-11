<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\CashFlow;
use App\Models\ProductOrder;
use App\Models\StockMovement;
use DB;

class ProductOrderObserver
{
    /**
     * Handle the ProductOrder "created" event.
     */
    public function created(ProductOrder $productOrder): void
    {

        try {
            DB::beginTransaction();

            $product = $productOrder->product;
            $order = $productOrder->order;

            // Cari account dengan kode 1-11 yang sesuai dengan team_id dari order
            $cashAccount = Account::where('code', '1-110')
                ->where('team_id', $order->team_id)
                ->firstOrFail(); // Menggunakan firstOrFail() untuk throw exception jika tidak ditemukan

            // Tambahkan transaksi debit ke akun kas
            CashFlow::create([
                'user_id' => $order->user_id,
                'team_id' => $order->team_id,
                'account_id' => $cashAccount->id,
                'transaction_date' => now()->format('Y-m-d'),
                'description' => 'Pesanan produk ' . $product->name . ' ' . $productOrder->qty . ' item',
                'amount' => $product->price * $productOrder->qty,
                'type' => 'debit',
            ]);

            // Tambahkan transaksi kredit ke akun produk
            CashFlow::create([
                'user_id' => $order->user_id,
                'team_id' => $order->team_id,
                'account_id' => $product->account_id,
                'transaction_date' => now()->format('Y-m-d'),
                'description' => 'Pesanan produk ' . $product->name . ' ' . $productOrder->qty . ' item',
                'amount' => $product->price * $productOrder->qty,
                'type' => 'credit',
            ]);

            StockMovement::create([
                'user_id' => $order->user_id,
                'team_id' => $order->team_id,
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $productOrder->qty,
                'total'=> $productOrder->qty,
                'notes' => 'Pengurangan stok ' . $product->name,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error untuk debugging
            \Log::error('Error processing ProductOrder: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            // Throw exception kembali untuk ditangani di level yang lebih tinggi
            throw new \Exception('Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Handle the ProductOrder "updated" event.
     */
    public function updated(ProductOrder $productOrder): void
    {
        //
    }

    /**
     * Handle the ProductOrder "deleted" event.
     */
    public function deleted(ProductOrder $productOrder): void
    {
        //
    }

    /**
     * Handle the ProductOrder "restored" event.
     */
    public function restored(ProductOrder $productOrder): void
    {
        //
    }

    /**
     * Handle the ProductOrder "force deleted" event.
     */
    public function forceDeleted(ProductOrder $productOrder): void
    {
        //
    }
}
