<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name', 'icon', 'route_name', 'parent_id',
        'group', 'sort_order', 'is_active', 'permission_name',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Returns the route pattern used to determine active state in the sidebar.
     * e.g. "dashboard" → "dashboard", "users.index" → "users.*"
     */
    public function getRoutePatternAttribute(): ?string
    {
        if (! $this->route_name) {
            return null;
        }

        $parts = explode('.', $this->route_name);

        return count($parts) > 1 ? $parts[0].'.*' : $this->route_name;
    }
}
