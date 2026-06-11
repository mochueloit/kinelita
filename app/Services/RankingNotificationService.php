<?php

namespace App\Services;

use App\Jobs\SendRankingNotificationBatch;
use App\Models\Participant;

class RankingNotificationService
{
    public function dispatch(): int
    {
        $participantIds = Participant::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->pluck('id');

        if ($participantIds->isEmpty()) {
            return 0;
        }

        $batchSize = config('kinela.notification_batch_size', 5);
        $delaySeconds = config('kinela.notification_batch_delay', 15);

        foreach ($participantIds->chunk($batchSize)->values() as $index => $chunk) {
            SendRankingNotificationBatch::dispatch($chunk->values()->all())
                ->delay(now()->addSeconds($index * $delaySeconds));
        }

        return $participantIds->count();
    }
}
