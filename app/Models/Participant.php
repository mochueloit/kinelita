<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'total_points'])]
class Participant extends Model
{
    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ParticipantComment::class)->latest();
    }
}
