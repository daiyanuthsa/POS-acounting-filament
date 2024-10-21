<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'accountName', 'accountType', 'asset_type'];

    protected static function boot()
    {
        parent::boot();

        // Otomatis mengisi user_id dengan ID user yang sedang login
        static::creating(function ($model) {
            $model->user_id = auth()->id(); // Ambil ID user yang login
        });
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function cashFlow(): HasMany
    {
        return $this->hasMany(CashFlow::class);
    }
}
