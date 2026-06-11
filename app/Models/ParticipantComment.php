<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['participant_id', 'name', 'email', 'comment'])]
class ParticipantComment extends Model
{
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}
