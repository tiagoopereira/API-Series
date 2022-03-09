<?php

namespace App\Models;

use App\Models\Episode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}