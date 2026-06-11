<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Participant;
use App\Models\Prediction;
use Illuminate\Support\Facades\DB;

class KinelaScoringService
{
    public function calculatePoints(
        int $predictedHome,
        int $predictedAway,
        int $actualHome,
        int $actualAway,
    ): int {
        if ($predictedHome === $actualHome && $predictedAway === $actualAway) {
            return 3;
        }

        if ($this->matchResult($predictedHome, $predictedAway) === $this->matchResult($actualHome, $actualAway)) {
            return 1;
        }

        return 0;
    }

    public function recalculateFixture(Fixture $fixture): void
    {
        if (! $fixture->is_played || $fixture->home_score === null || $fixture->away_score === null) {
            return;
        }

        $fixture->predictions()->each(function (Prediction $prediction) use ($fixture) {
            $points = $this->calculatePoints(
                $prediction->home_score,
                $prediction->away_score,
                $fixture->home_score,
                $fixture->away_score,
            );

            $prediction->update(['points_earned' => $points]);
        });
    }

    public function recalculateAll(): void
    {
        DB::transaction(function () {
            Fixture::query()
                ->where('is_played', true)
                ->whereNotNull('home_score')
                ->whereNotNull('away_score')
                ->each(fn (Fixture $fixture) => $this->recalculateFixture($fixture));

            $this->updateParticipantTotals();
        });
    }

    public function updateParticipantTotals(): void
    {
        Participant::query()->each(function (Participant $participant) {
            $total = $participant->predictions()->sum('points_earned');

            $participant->update(['total_points' => $total]);
        });
    }

    private function matchResult(int $home, int $away): string
    {
        if ($home > $away) {
            return 'home';
        }

        if ($home < $away) {
            return 'away';
        }

        return 'draw';
    }
}
