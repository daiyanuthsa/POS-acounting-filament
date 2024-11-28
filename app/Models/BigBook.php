<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BigBook extends Model
{
    use HasFactory;
    // Specify the table associated with the model
    protected $table = 'bigbook_view';

    // Views typically don't have a primary key
    protected $primaryKey = 'bigbook_id';

    // Disable timestamps for the view model
    public $timestamps = false;

    // Allow mass assignment for all the columns in the view
    protected $guarded = [];

    protected $casts = [
        'debit' => MoneyCast::class,
        'credit' => MoneyCast::class,
        'running_balance' => MoneyCast::class,
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
