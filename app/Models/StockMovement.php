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
    protected static function boot()
    {
        parent::boot();

        // Otomatis mengisi user_id dengan ID user yang sedang login
        static::creating(function ($model) {
            if (!$model->user_id) {
                $model->user_id = auth()->id();// Ambil ID user yang login
            }
            if (!$model->team_id) {
                $model->team_id = auth()->user()->currentTeam->id;
            }
        });
    }
}
