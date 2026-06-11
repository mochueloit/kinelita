<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Participant;
use Illuminate\View\View;

class ParticipantProfileController extends Controller
{
    public function show(Participant $participant): View
    {
        $fixtures = Fixture::orderBy('match_number')->get();
        $predictions = $participant->predictions()->get()->keyBy('fixture_id');

        $rankedIds = Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->pluck('id');

        $position = $rankedIds->search($participant->id);
        $position = $position === false ? null : $position + 1;

        $comments = $participant->comments()->limit(50)->get();

        $playedCount = Fixture::query()->where('is_played', true)->count();
        $totalFixtures = Fixture::query()->count();

        return view('participants.show', compact(
            'participant',
            'fixtures',
            'predictions',
            'position',
            'comments',
            'playedCount',
            'totalFixtures',
        ));
    }
}
