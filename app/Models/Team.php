<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'location'];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Account::class);
    }
    public function cashFlows(): HasMany
    {
        return $this->hasMany(CashFlow::class);
    }
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

}