<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'account_id',
        'user_id',
        'name',
        'description',
        'price',
        'upc_id',
        'stock_id'
    ];

    protected $casts = [
        'price' => MoneyCast::class,
    ];

    // Relasi ke Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function upcAccount()
    {
        return $this->belongsTo(Account::class,'upc_id');
    }
    public function stockAccount()
    {
        return $this->belongsTo(Account::class, 'stock_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_orders', 'product_id', 'order_id')
            ->withPivot('qty');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->user_id) {
                $model->user_id = auth()->id();
            }
            if (!$model->team_id) {
                $model->team_id = auth()->user()->currentTeam->id;
            }
        });
    }
}
