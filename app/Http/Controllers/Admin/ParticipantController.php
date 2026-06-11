<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ParticipantPredictionsMail;
use App\Models\Fixture;
use App\Models\Participant;
use App\Models\Prediction;
use App\Services\ActivityLogService;
use App\Services\KinelaScoringService;
use App\Services\ParticipantPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends Controller
{
    public function index(): View
    {
        $participants = Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->get();

        return view('admin.participants.index', compact('participants'));
    }

    public function create(): View
    {
        $fixtures = Fixture::orderBy('match_number')->get();

        return view('admin.participants.create', compact('fixtures'));
    }

    public function store(Request $request, KinelaScoringService $scoringService, ActivityLogService $activityLog): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:participants,email'],
            'predictions' => ['required', 'array'],
            'predictions.*.home_score' => ['required', 'integer', 'min:0', 'max:20'],
            'predictions.*.away_score' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $participant = Participant::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $fixtures = Fixture::orderBy('match_number')->get();

        foreach ($fixtures as $fixture) {
            $prediction = $data['predictions'][$fixture->id] ?? null;

            if ($prediction === null) {
                continue;
            }

            Prediction::create([
                'participant_id' => $participant->id,
                'fixture_id' => $fixture->id,
                'home_score' => $prediction['home_score'],
                'away_score' => $prediction['away_score'],
            ]);
        }

        $scoringService->recalculateAll();

        $activityLog->log(
            'participant.created',
            "Participante registrado: {$participant->name}",
            $participant,
            ['email' => $participant->email, 'predictions_count' => $fixtures->count()],
            $request,
        );

        return redirect()
            ->route('admin.participants.index')
            ->with('success', 'Participante registrado correctamente.');
    }

    public function edit(Participant $participant): View
    {
        $fixtures = Fixture::orderBy('match_number')->get();
        $predictions = $participant->predictions()->get()->keyBy('fixture_id');

        return view('admin.participants.edit', compact('participant', 'fixtures', 'predictions'));
    }

    public function update(Request $request, Participant $participant, KinelaScoringService $scoringService, ActivityLogService $activityLog): RedirectResponse
    {
        $previous = $participant->only(['name', 'email', 'total_points']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('participants', 'email')->ignore($participant->id)],
            'predictions' => ['required', 'array'],
            'predictions.*.home_score' => ['required', 'integer', 'min:0', 'max:20'],
            'predictions.*.away_score' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $participant->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $fixtures = Fixture::orderBy('match_number')->get();

        foreach ($fixtures as $fixture) {
            $predictionData = $data['predictions'][$fixture->id] ?? null;

            if ($predictionData === null) {
                continue;
            }

            Prediction::updateOrCreate(
                [
                    'participant_id' => $participant->id,
                    'fixture_id' => $fixture->id,
                ],
                [
                    'home_score' => $predictionData['home_score'],
                    'away_score' => $predictionData['away_score'],
                ],
            );
        }

        $scoringService->recalculateAll();

        $participant->refresh();

        $activityLog->log(
            'participant.updated',
            "Participante actualizado: {$participant->name}",
            $participant,
            [
                'before' => $previous,
                'after' => $participant->only(['name', 'email', 'total_points']),
            ],
            $request,
        );

        return redirect()
            ->route('admin.participants.index')
            ->with('success', 'Participante actualizado correctamente.');
    }

    public function destroy(Participant $participant, ActivityLogService $activityLog): RedirectResponse
    {
        $snapshot = $participant->only(['id', 'name', 'email', 'total_points']);

        $participant->delete();

        $activityLog->log(
            'participant.deleted',
            "Participante eliminado: {$snapshot['name']}",
            properties: ['participant' => $snapshot],
        );

        return redirect()
            ->route('admin.participants.index')
            ->with('success', 'Participante eliminado.');
    }

    public function exportPdf(Participant $participant, ParticipantPdfService $pdfService): Response
    {
        return $pdfService->download($participant);
    }

    public function emailPredictions(
        Request $request,
        Participant $participant,
        ActivityLogService $activityLog,
    ): RedirectResponse {
        if (blank($participant->email)) {
            return back()->withErrors([
                'email' => "El participante {$participant->name} no tiene correo registrado.",
            ]);
        }

        $totalParticipants = Participant::count();
        $position = $this->participantPosition($participant) ?? $totalParticipants;

        Mail::to($participant->email)->queue(new ParticipantPredictionsMail(
            $participant,
            $position,
            $totalParticipants,
        ));

        $activityLog->log(
            'participant.predictions_emailed',
            "Kinela PDF enviada por correo a {$participant->name}",
            $participant,
            ['email' => $participant->email],
            $request,
        );

        return back()->with(
            'success',
            "Kinela en PDF encolada para envío a {$participant->email}. Asegúrate de tener el worker de cola activo.",
        );
    }

    private function participantPosition(Participant $participant): ?int
    {
        $rankedIds = Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->pluck('id');

        $index = $rankedIds->search($participant->id);

        return $index === false ? null : $index + 1;
    }
}
