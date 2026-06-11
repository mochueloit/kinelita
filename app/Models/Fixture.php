<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'match_number',
    'group_name',
    'match_date',
    'home_team',
    'away_team',
    'home_score',
    'away_score',
    'is_played',
])]
class Fixture extends Model
{
    protected function casts(): array
    {
        return [
            'match_date' => 'date',
            'is_played' => 'boolean',
        ];
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }
}
