<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function processUnrecordedOrders()
    {
        // Ambil semua order yang is_record = false
        $orders = Order::where('is_record', false)->get();

        foreach ($orders as $order) {
            // Cari account dengan kode 1-11 yang sesuai dengan team_id dari order
            $creditAccount = Account::where('code', '1-11')
                ->where('team_id', $order->team_id) // Pastikan account dari tim yang sama
                ->first();

            if (!$creditAccount) {
                \Log::error('Account with code 1-11 not found for team ' . $order->team_id);
                continue; // Jika tidak ditemukan, lewati order ini
            }

            // Ambil semua produk untuk order ini
            $products = $order->products; // Pastikan ada relasi di model Order

            foreach ($products as $product) {
                // Ambil account_id dari produk
                $accountId = $product->account_id;

                // Cek apakah account_id ada dan user_id tersedia
                if ($accountId && $order->user_id) {
                    // Pastikan akun produk berasal dari tim yang sama
                    $productAccount = Account::where('id', $accountId)
                        ->where('team_id', $order->team_id) // Sesuaikan dengan team_id dari order
                        ->first();

                    if (!$productAccount) {
                        \Log::error('Product account ' . $accountId . ' not found for team ' . $order->team_id);
                        continue; // Jika akun produk tidak sesuai, lewati produk ini
                    }

                    // Tambahkan transaksi debit ke akun dengan kode 1-11
                    CashFlow::create([
                        'user_id' => $order->user_id, // User ID dari order
                        'team_id' => $order->team_id, // Team ID dari order
                        'account_id' => $creditAccount->id, // Kredit ke akun 1-11
                        'transaction_date' => now()->format('Y-m-d'),
                        'description' => 'Order ' . $order->id . ' product ' . $product->name,
                        'amount' => $product->price * $product->pivot->qty,
                        'type' => 'debit',
                    ]);

                    // Tambahkan transaksi kredit ke akun produk
                    CashFlow::create([
                        'user_id' => $order->user_id, // User ID dari order
                        'team_id' => $order->team_id, // Team ID dari order
                        'account_id' => $productAccount->id, // Debit dari account produk
                        'transaction_date' => now()->format('Y-m-d'),
                        'description' => 'Order ' . $order->id . ' product ' . $product->id,
                        'amount' => $product->price * $product->pivot->qty, // total amount
                        'type' => 'credit',
                    ]);
                } else {
                    \Log::error('Account ID or User ID not found for product ' . $product->id);
                }
            }

            // Update is_record menjadi true setelah diproses
            $order->update(['is_record' => true]);
        }

        return response()->json(['message' => 'Unrecorded orders processed and updated successfully.']);
    }



}
