<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\RankingComment;
use Illuminate\View\View;

class RankingController extends Controller
{
    public function index(): View
    {
        $participants = Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->get();

        $comments = RankingComment::query()
            ->latest()
            ->limit(50)
            ->get();

        return view('ranking', compact('participants', 'comments'));
    }
}
