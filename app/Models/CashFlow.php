<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'user_id',
        'team_id',
        'transaction_date',
        'description',
        'amount',
        'type'
    ];

    protected $casts = [
        'amount' => MoneyCast::class,
        'transaction_date' => 'date',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
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
