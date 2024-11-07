<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'accountName', 'accountType', 'asset_type', 'team_id', 'parent_id'];

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

    // Relasi ke child accounts
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    // Relasi ke parent account
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    // Jika ingin mendapatkan semua children secara rekursif
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
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
