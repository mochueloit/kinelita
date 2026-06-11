<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Services\ActivityLogService;
use App\Services\KinelaScoringService;
use App\Services\RankingNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FixtureController extends Controller
{
    public function index(): View
    {
        $fixtures = Fixture::orderBy('match_number')->get()->groupBy('group_name');

        return view('admin.fixtures.index', compact('fixtures'));
    }

    public function update(
        Request $request,
        KinelaScoringService $scoringService,
        RankingNotificationService $notificationService,
        ActivityLogService $activityLog,
    ): RedirectResponse {
        $data = $request->validate([
            'fixtures' => ['required', 'array'],
            'fixtures.*.home_score' => ['nullable', 'integer', 'min:0', 'max:20'],
            'fixtures.*.away_score' => ['nullable', 'integer', 'min:0', 'max:20'],
            'notify_participants' => ['sometimes', 'boolean'],
        ]);

        $changes = [];

        foreach ($data['fixtures'] as $fixtureId => $scores) {
            $fixture = Fixture::find($fixtureId);

            if ($fixture === null) {
                continue;
            }

            $homeScore = $scores['home_score'];
            $awayScore = $scores['away_score'];
            $before = [
                'home_score' => $fixture->home_score,
                'away_score' => $fixture->away_score,
                'is_played' => $fixture->is_played,
            ];

            if ($homeScore === null || $awayScore === null || $homeScore === '' || $awayScore === '') {
                $fixture->update([
                    'home_score' => null,
                    'away_score' => null,
                    'is_played' => false,
                ]);

                $fixture->predictions()->update(['points_earned' => 0]);

                if ($before['is_played'] || $before['home_score'] !== null) {
                    $changes[] = [
                        'match_number' => $fixture->match_number,
                        'match' => "{$fixture->home_team} vs {$fixture->away_team}",
                        'before' => $before,
                        'after' => ['home_score' => null, 'away_score' => null, 'is_played' => false],
                    ];
                }

                continue;
            }

            $newHome = (int) $homeScore;
            $newAway = (int) $awayScore;

            if ($before['home_score'] !== $newHome || $before['away_score'] !== $newAway || ! $before['is_played']) {
                $changes[] = [
                    'match_number' => $fixture->match_number,
                    'match' => "{$fixture->home_team} vs {$fixture->away_team}",
                    'before' => $before,
                    'after' => ['home_score' => $newHome, 'away_score' => $newAway, 'is_played' => true],
                ];
            }

            $fixture->update([
                'home_score' => $newHome,
                'away_score' => $newAway,
                'is_played' => true,
            ]);

            $scoringService->recalculateFixture($fixture);
        }

        $scoringService->updateParticipantTotals();

        $message = 'Resultados guardados y ranking actualizado.';
        $queued = 0;

        if ($request->boolean('notify_participants')) {
            $queued = $notificationService->dispatch();

            if ($queued > 0) {
                $message .= " Se encolaron {$queued} notificaciones por correo.";
            }
        }

        if ($changes !== [] || $queued > 0) {
            $activityLog->log(
                'fixtures.results_saved',
                'Resultados de partidos guardados y puntos recalculados',
                properties: [
                    'fixtures_changed' => count($changes),
                    'changes' => $changes,
                    'notifications_queued' => $queued,
                    'top_3' => \App\Models\Participant::query()
                        ->orderByDesc('total_points')
                        ->limit(3)
                        ->get(['name', 'total_points'])
                        ->toArray(),
                ],
                request: $request,
            );
        }

        return redirect()
            ->route('admin.fixtures.index')
            ->with('success', $message);
    }
}
