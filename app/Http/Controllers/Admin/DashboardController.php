<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Participant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $participantsCount = Participant::count();
        $participantsWithEmail = Participant::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->count();
        $fixturesPlayed = Fixture::where('is_played', true)->count();
        $totalFixtures = Fixture::count();

        return view('admin.dashboard', compact(
            'participantsCount',
            'participantsWithEmail',
            'fixturesPlayed',
            'totalFixtures',
        ));
    }
}
