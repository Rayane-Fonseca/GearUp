<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Content extends Model
{
    protected $fillable = [
        'area_id', 'type', 'title', 'author', 'url',
        'duration', 'language', 'is_free', 'has_certificate',
        'description', 'cover', 'is_active',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'has_certificate' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function trails(): BelongsToMany
    {
        return $this->belongsToMany(Trail::class, 'trail_items')->withPivot('order')->withTimestamps();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}