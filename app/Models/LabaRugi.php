<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabaRugi extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'laba_rugi'; // Sesuaikan dengan nama view

    // Views typically don't have a primary key
    // protected $primaryKey = 'bigbook_id'; // Sesuaikan dengan primary key dari view jika ada

    // Disable timestamps for the view model
    public $timestamps = false;

    // Allow mass assignment for all the columns in the view
    protected $guarded = [];

    // Define the cast types for specific columns
    protected $casts = [
        'debit' => MoneyCast::class,
        'credit' => MoneyCast::class,
    ];

    // Relasi dengan model Team
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Relasi dengan model Account
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
