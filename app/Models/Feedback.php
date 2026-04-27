<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'status',
        'admin_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'kritik' => 'Kritik',
            'saran'  => 'Saran',
            default  => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'reviewed' => 'Ditinjau',
            'resolved' => 'Selesai',
            default    => $this->status,
        };
    }
}
