<?php

namespace App\Models;

use App\Models\Episode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    protected $perPage = 5;
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $appends = ['links'];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function getLinksAttribute(): array
    {
        return [
            'self' => "/api/series/{$this->id}",
            'episodes' => "/api/series/{$this->id}/episodes"
        ];
    }
}