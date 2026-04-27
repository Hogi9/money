<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'type', 'description', 'user_id', 'team_id'];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function transactionNames(): HasMany
    {
        return $this->hasMany(TransactionName::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'income' ? 'Income' : 'Outcome';
    }
}
