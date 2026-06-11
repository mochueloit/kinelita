<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ParticipantPdfService
{
    public function filename(Participant $participant): string
    {
        return 'kinela-'.Str::slug($participant->name).'.pdf';
    }

    public function viewData(Participant $participant): array
    {
        $fixtures = Fixture::query()
            ->orderBy('match_date')
            ->orderBy('match_number')
            ->get();

        $predictions = $participant->predictions()->get()->keyBy('fixture_id');

        $rankedIds = Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->pluck('id');

        $index = $rankedIds->search($participant->id);
        $position = $index === false ? null : $index + 1;

        return compact('participant', 'fixtures', 'predictions', 'position');
    }

    public function output(Participant $participant): string
    {
        return Pdf::loadView('admin.participants.pdf', $this->viewData($participant))
            ->setPaper('a4', 'portrait')
            ->output();
    }

    public function download(Participant $participant): Response
    {
        $pdf = Pdf::loadView('admin.participants.pdf', $this->viewData($participant))
            ->setPaper('a4', 'portrait');

        return $pdf->download($this->filename($participant));
    }
}
