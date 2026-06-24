<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrailItem extends Model
{
    protected $fillable = ['trail_id', 'content_id', 'order'];

    public function trail(): BelongsTo
    {
        return $this->belongsTo(Trail::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}