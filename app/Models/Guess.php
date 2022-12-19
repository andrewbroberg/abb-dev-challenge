<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guess extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
