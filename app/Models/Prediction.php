<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'participant_id',
    'fixture_id',
    'home_score',
    'away_score',
    'points_earned',
])]
class Prediction extends Model
{
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }
}
