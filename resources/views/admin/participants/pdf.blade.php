<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Kinela — {{ $participant->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .meta { color: #64748b; margin-bottom: 14px; }
        .meta span { margin-right: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 6px; text-align: left; vertical-align: middle; }
        th { background: #e0f2fe; font-size: 9px; text-transform: uppercase; }
        .score { text-align: center; font-weight: bold; white-space: nowrap; }
        .pts { text-align: center; color: #0284c7; font-weight: bold; }
        .played { background: #f0fdf4; }
        .match { font-size: 10px; }
        .real { color: #64748b; font-size: 9px; }
        footer { margin-top: 16px; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <h1>{{ $participant->name }}</h1>
    <p class="meta">
        @if ($position)
            <span>Posición: #{{ $position }}</span>
        @endif
        <span>Puntos: {{ $participant->total_points }}</span>
        @if ($participant->email)
            <span>{{ $participant->email }}</span>
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 24px;">#</th>
                <th style="width: 42px;">Fecha</th>
                <th>Partido</th>
                <th style="width: 72px;">Pronóstico</th>
                <th style="width: 28px;">Pts</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fixtures as $fixture)
                @php
                    $prediction = $predictions[$fixture->id] ?? null;
                    $rowClass = $fixture->is_played ? 'played' : '';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $fixture->match_number }}</td>
                    <td>{{ $fixture->match_date?->format('d/m') }}</td>
                    <td class="match">{{ $fixture->home_team }} vs {{ $fixture->away_team }}</td>
                    <td class="score">
                        {{ $prediction?->home_score ?? '—' }} - {{ $prediction?->away_score ?? '—' }}
                        @if ($fixture->is_played)
                            <br><span class="real">Real: {{ $fixture->home_score }}-{{ $fixture->away_score }}</span>
                        @endif
                    </td>
                    <td class="pts">{{ $fixture->is_played ? ($prediction?->points_earned ?? 0) : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>Kinela Mundial FIFA 2026 — Generado {{ now()->format('d/m/Y H:i') }}</footer>
</body>
</html>
