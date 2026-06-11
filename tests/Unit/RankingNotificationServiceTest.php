<?php

namespace Tests\Unit;

use App\Jobs\SendRankingNotificationBatch;
use App\Models\Participant;
use App\Services\RankingNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class RankingNotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatches_participants_in_batches_of_five(): void
    {
        Bus::fake();

        for ($i = 1; $i <= 12; $i++) {
            Participant::create([
                'name' => "Participante {$i}",
                'email' => "user{$i}@test.com",
            ]);
        }

        $queued = app(RankingNotificationService::class)->dispatch();

        $this->assertSame(12, $queued);
        Bus::assertDispatched(SendRankingNotificationBatch::class, 3);
    }

    public function test_returns_zero_when_no_emails(): void
    {
        Bus::fake();

        Participant::create(['name' => 'Sin correo']);

        $queued = app(RankingNotificationService::class)->dispatch();

        $this->assertSame(0, $queued);
        Bus::assertNothingDispatched();
    }
}
