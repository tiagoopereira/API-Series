<?php

namespace App\Models;

use App\Models\Serie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    public $timestamps = false;
    protected $fillable = ['season', 'number', 'watched', 'serie_id'];
    protected $appends = ['links'];

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function getWatchedAttribute(string|int|bool $watched): bool
    {
        return $watched;
    }

    public function getLinksAttribute(): array
    {
        return [
            'self' => "/api/episodes/{$this->id}",
            'serie' => "/api/series/{$this->serie_id}"
        ];
    }
}