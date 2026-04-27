<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Wallet extends Model
{
    protected $fillable = ['name', 'description', 'balance', 'user_id', 'team_id'];

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

    protected $casts = ['balance' => 'decimal:2'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_wallet_id');
    }
}
