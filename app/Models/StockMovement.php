<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'type',
        'quantity',
        'unit_cost',
        'total',
        'notes',
        'team_id',
        'user_id',
        'remaining_quantity',
       'is_active'
    ] ;
    protected $casts = [
        'total' => MoneyCast::class,
        'unit_cost' => MoneyCast::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
