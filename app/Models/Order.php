<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_type',
        'payment_amount',
        'is_record',
        'created_at',
        'transaction_id',
    ];
    protected $casts = [
        'payment_amount' => MoneyCast::class,
        'created_at' => 'datetime',
        'is_record' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {

        return $this->hasMany(ProductOrder::class, 'order_id');
    }
    public function productOrder()
    {
        return $this->belongsToMany(Product::class, 'product_orders', 'order_id', 'product_id')
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
        static::creating(function ($model) {
            // Generate custom transaction ID
            $model->transaction_id = $model->generateTransactionId();
        });
    }

    public function generateTransactionId(): string
    {
        $tenant = auth()->user()->teams()->first(); // ID tenant dari model

        // Format: TX-TENANT-{tenant_id}-20241211-00001
        $date = now()->format('Ymd'); // Format tanggal

        // Cari jumlah transaksi yang sudah ada berdasarkan team_id dan tanggal saat ini
        $transactionCount = self::where('team_id', $tenant->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        // Sequence urut untuk setiap tenant
        $sequence = $transactionCount + 1;

        // Format sequence menjadi 5 digit
        $sequenceFormatted = str_pad($sequence, 5, '0', STR_PAD_LEFT);

        return "TX-{$tenant->id}-{$date}-{$sequenceFormatted}";
    }
}
