<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'type', 'amount', 'transfer_fee', 'fee_bearer', 'wallet_id', 'to_wallet_id',
        'transaction_name_id', 'category_id', 'description',
        'date', 'user_id', 'team_id',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'transfer_fee' => 'decimal:2',
        'date'         => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function toWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    public function transactionName(): BelongsTo
    {
        return $this->belongsTo(TransactionName::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'income'   => 'Balance In',
            'outcome'  => 'Balance Out',
            'transfer' => 'Moving Balance',
            default    => $this->type,
        };
    }
}
