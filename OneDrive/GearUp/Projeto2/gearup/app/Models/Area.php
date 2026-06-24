<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function trails(): HasMany
    {
        return $this->hasMany(Trail::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}