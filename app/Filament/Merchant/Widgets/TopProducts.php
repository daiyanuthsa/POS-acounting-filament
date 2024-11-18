<?php

namespace App\Filament\Merchant\Widgets;

use Auth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
use App\Models\ProductOrder;

class TopProducts extends BaseWidget
{

    protected static ?string $heading = 'Produk Terlaris';
    public function table(Table $table): Table
    {
        $teamId = Auth::user()->teams()->first()->id;
        return $table
            ->query(
                Product::query()
                    ->where('team_id', $teamId)
                    ->whereMonth('product_orders.created_at', now()->month)
                    ->select([
                        'products.id',
                        'products.name',
                        'products.price',
                        \DB::raw('SUM(product_orders.qty) as total_sold')
                    ])
                    ->join('product_orders', 'products.id', '=', 'product_orders.product_id')
                    ->groupBy('products.id', 'products.name')
                    ->orderByDesc('total_sold')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk'),

                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Total Terjual')
                    ->sortable()
                    ->alignRight(),
            ])
            ->defaultSort('total_sold', 'desc')
            ->striped()
            ->paginated(false);

    }
}
