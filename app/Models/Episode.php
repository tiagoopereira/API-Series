<?php

namespace App\Models;

use App\Models\Serie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    public $timestamps = false;
    protected $fillable = ['season', 'number', 'watched', 'serie_id'];

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }
}