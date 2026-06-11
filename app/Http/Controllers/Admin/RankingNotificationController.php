<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\RankingNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RankingNotificationController extends Controller
{
    public function store(Request $request, RankingNotificationService $notificationService, ActivityLogService $activityLog): RedirectResponse
    {
        $queued = $notificationService->dispatch();

        if ($queued === 0) {
            return back()->withErrors([
                'notifications' => 'No hay participantes con correo electrónico registrado.',
            ]);
        }

        $batchSize = config('kinela.notification_batch_size', 5);
        $batches = (int) ceil($queued / $batchSize);

        $activityLog->log(
            'notifications.ranking_dispatched',
            "Notificaciones de ranking encoladas ({$queued})",
            properties: [
                'recipients' => $queued,
                'batches' => $batches,
                'batch_size' => $batchSize,
            ],
            request: $request,
        );

        return back()->with(
            'success',
            "Se encolaron {$queued} notificaciones en {$batches} lote(s) de {$batchSize}. Asegúrate de tener el worker de cola activo.",
        );
    }
}
