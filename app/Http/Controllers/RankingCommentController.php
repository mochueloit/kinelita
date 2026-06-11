<?php

namespace App\Http\Controllers;

use App\Models\RankingComment;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RankingCommentController extends Controller
{
    public function store(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()->route('ranking')->with('success', '¡Comentario publicado!');
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'comment' => ['required', 'string', 'min:3', 'max:500'],
            'robot_check' => ['nullable', 'string', 'max:0'],
        ], [
            'robot_check.max' => 'Deja ese campo vacío si eres humano.',
        ]);

        $comment = RankingComment::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'],
            'comment' => $data['comment'],
        ]);

        $activityLog->log(
            'comment.created',
            'Nuevo comentario en el ranking',
            $comment,
            [
                'name' => $comment->name,
                'email' => $comment->email,
                'comment_preview' => mb_strimwidth($comment->comment, 0, 120, '…'),
            ],
            $request,
        );

        return redirect()
            ->route('ranking')
            ->withFragment('comentarios')
            ->with('success', '¡Comentario publicado! Gracias por participar.');
    }
}
