<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\ParticipantComment;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ParticipantCommentController extends Controller
{
    public function store(Request $request, Participant $participant, ActivityLogService $activityLog): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()
                ->route('participants.show', $participant)
                ->withFragment('comentarios')
                ->with('success', '¡Comentario publicado!');
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'comment' => ['required', 'string', 'min:3', 'max:500'],
            'robot_check' => ['nullable', 'string', 'max:0'],
        ], [
            'robot_check.max' => 'Deja ese campo vacío si eres humano.',
        ]);

        $comment = ParticipantComment::create([
            'participant_id' => $participant->id,
            'name' => $data['name'] ?? null,
            'email' => $data['email'],
            'comment' => $data['comment'],
        ]);

        $activityLog->log(
            'participant_comment.created',
            "Comentario en kinela de {$participant->name}",
            $comment,
            [
                'participant_id' => $participant->id,
                'participant_name' => $participant->name,
                'comment_preview' => mb_strimwidth($comment->comment, 0, 120, '…'),
            ],
            $request,
        );

        return redirect()
            ->route('participants.show', $participant)
            ->withFragment('comentarios')
            ->with('success', '¡Comentario publicado en la kinela de '.$participant->name.'!');
    }
}
