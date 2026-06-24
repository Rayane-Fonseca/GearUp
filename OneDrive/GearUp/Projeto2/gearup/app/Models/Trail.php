<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trail extends Model
{
    protected $fillable = [
        'area_id', 'created_by', 'title', 'description',
        'type', 'cover', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'trail_items')->withPivot('order')->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(TrailItem::class)->orderBy('order');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}