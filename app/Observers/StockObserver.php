<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\CashFlow;
use App\Models\StockMovement;
use DB;
use Log;

class StockObserver
{
    /**
     * Handle the StockMovement "created" event.
     *
     * @param  StockMovement  $stockMovement
     * @return void
     */
    public function created(StockMovement $stockMovement)
    {
        try {
            DB::beginTransaction();

            if ($stockMovement->type === 'in') {
                $this->handleStockIn($stockMovement);
            } elseif ($stockMovement->type === 'out') {
                $this->handleStockOut($stockMovement);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing stock movement: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle stock in movement
     *
     * @param  StockMovement  $stockMovement
     * @return void
     */
    private function handleStockIn(StockMovement $stockMovement)
    {
        // Set initial values
        $stockMovement->remaining_quantity = $stockMovement->quantity;
        $stockMovement->is_active = true;
        $stockMovement->save();

        // Create financial transactions
        $this->createInventoryTransaction($stockMovement);
        $this->createCashTransaction($stockMovement);
    }

    /**
     * Membuat transaksi debit pada akun persediaan (inventory)
     *
     * @param  StockMovement  $stockMovement
     * @return void
     */
    private function createInventoryTransaction(StockMovement $stockMovement)
    {
        CashFlow::create([
            'user_id' => $stockMovement->user_id,
            'team_id' => $stockMovement->team_id,
            'account_id' => $stockMovement->product->stock_id, // Menggunakan stock_id dari produk terkait
            'transaction_date' => now(),
            'description' => 'Penambahan stok untuk ' . $stockMovement->product->name,
            'amount' => $stockMovement->total,
            'type' => 'debit',
        ]);
    }

    /**
     * Membuat transaksi credit pada akun kas
     *
     * @param  StockMovement  $stockMovement
     * @return void
     */
    private function createCashTransaction(StockMovement $stockMovement)
    {
        // Ambil akun kas untuk tim terkait (sesuaikan dengan kebutuhan Anda)
        $cashAccount = Account::where('team_id', $stockMovement->team_id)
            ->where('accountType', 'Asset')
            ->where('code', 'like', '1-110') // Sesuaikan dengan nama atau kriteria akun kas
            ->first();

        if ($cashAccount) {
            CashFlow::create([
                'user_id' => $stockMovement->user_id,
                'team_id' => $stockMovement->team_id,
                'account_id' => $cashAccount->id,
                'transaction_date' => now(),
                'description' => 'Pengurangan kas untuk stok ' . $stockMovement->product->name,
                'amount' => $stockMovement->total,
                'type' => 'credit',
            ]);
        }
    }

    /**
     * Handle stock out movement using FIFO method
     *
     * @param  StockMovement  $stockMovement
     * @return void
     */
    private function handleStockOut(StockMovement $stockMovement)
    {
        // Get active stock in movements ordered by oldest first (FIFO)
        $activeStockIns = StockMovement::where('product_id', $stockMovement->product_id)
            ->where('type', 'in')
            ->where('is_active', true)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingToProcess = $stockMovement->quantity;
        $totalCost = 0;
        $stockOutDetails = [];

        foreach ($activeStockIns as $stockIn) {
            if ($remainingToProcess <= 0) {
                break;
            }

            $quantityToDeduct = min($remainingToProcess, $stockIn->remaining_quantity);
            $costPerUnit = $stockIn->total / $stockIn->quantity;
            $costForThis = $quantityToDeduct * $costPerUnit;

            // Update remaining quantity for stock in
            $stockIn->remaining_quantity -= $quantityToDeduct;
            if ($stockIn->remaining_quantity <= 0) {
                $stockIn->is_active = false;
            }
            $stockIn->save();

            // Track details for this stock out
            $stockOutDetails[] = [
                'stock_in_id' => $stockIn->id,
                'quantity' => $quantityToDeduct,
                'cost' => $costForThis
            ];

            $remainingToProcess -= $quantityToDeduct;
            $totalCost += $costForThis;
        }

        if ($remainingToProcess > 0) {
            throw new \Exception('Insufficient stock available for product: ' . $stockMovement->product_id);
        }

        // Update stock out movement with the calculated total cost
        $stockMovement->total = $totalCost;
        $stockMovement->remaining_quantity = 0; // Stock out movements don't track remaining quantity
        $stockMovement->save();

        // You might want to store the stock out details in a separate table
        // $this->saveStockOutDetails($stockMovement->id, $stockOutDetails);

        // Create financial transactions for stock out
        $this->createStockOutTransactions($stockMovement);
    }

    /**
     * Create financial transactions for stock out
     *
     * @param  StockMovement  $stockMovement
     * @return void
     */
    private function createStockOutTransactions(StockMovement $stockMovement)
    {
        // Get necessary accounts
        $product = $stockMovement->product;

        // Get UPC and Supply accounts
        $upcAccount = $product->upc_id;
        $supplyAccount = $product->stock_id;

        if (!$upcAccount || !$supplyAccount) {
            throw new \Exception('Required accounts not found for stock out transaction');
        }

        // Cost of Goods Sold entry (Debit UPC)
        CashFlow::create([
            'user_id' => $stockMovement->user_id,
            'team_id' => $stockMovement->team_id,
            'account_id' => $upcAccount,
            'transaction_date' => now(),
            'description' => 'HPP untuk ' . $product->name,
            'amount' => $stockMovement->total,
            'type' => 'debit',
        ]);

        // Reduce Inventory (Credit Supply/Stock)
        CashFlow::create([
            'user_id' => $stockMovement->user_id,
            'team_id' => $stockMovement->team_id,
            'account_id' => $supplyAccount,
            'transaction_date' => now(),
            'description' => 'Pengurangan persediaan untuk ' . $product->name,
            'amount' => $stockMovement->total,
            'type' => 'credit',
        ]);
    }

    /**
     * Handle the StockMovement "updated" event.
     */
    public function updated(StockMovement $stockMovement): void
    {
        // Update is_active jika remaining_quantity mencapai 0
        if ($stockMovement->remaining_quantity <= 0 && $stockMovement->is_active) {
            $stockMovement->is_active = false;
            $stockMovement->save();
        }
    }

    /**
     * Handle the StockMovement "deleted" event.
     */
    public function deleted(StockMovement $stockMovement): void
    {
        //
    }

    /**
     * Handle the StockMovement "restored" event.
     */
    public function restored(StockMovement $stockMovement): void
    {
        //
    }

    /**
     * Handle the StockMovement "force deleted" event.
     */
    public function forceDeleted(StockMovement $stockMovement): void
    {
        //
    }
}
