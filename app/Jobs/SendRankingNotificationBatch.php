<?php

namespace App\Jobs;

use App\Mail\RankingUpdateNotification;
use App\Models\Participant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendRankingNotificationBatch implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    /**
     * @param  array<int>  $participantIds
     */
    public function __construct(public array $participantIds) {}

    public function handle(): void
    {
        $ranking = $this->buildRanking();
        $positions = $this->buildPositions($ranking);

        foreach ($this->participantIds as $participantId) {
            $participant = $ranking->firstWhere('id', $participantId);

            if ($participant === null || blank($participant->email)) {
                continue;
            }

            Mail::to($participant->email)->send(
                new RankingUpdateNotification(
                    participant: $participant,
                    position: $positions[$participant->id],
                    totalParticipants: $ranking->count(),
                    topRanking: $ranking->take(5),
                ),
            );
        }
    }

    /**
     * @return Collection<int, Participant>
     */
    private function buildRanking(): Collection
    {
        return Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  Collection<int, Participant>  $ranking
     * @return array<int, int>
     */
    private function buildPositions(Collection $ranking): array
    {
        $positions = [];

        foreach ($ranking->values() as $index => $participant) {
            $positions[$participant->id] = $index + 1;
        }

        return $positions;
    }
}
