<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Kinela — {{ $participant->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .meta { color: #64748b; margin-bottom: 16px; }
        .meta span { margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 6px; text-align: left; }
        th { background: #e0f2fe; font-size: 10px; text-transform: uppercase; }
        .group { background: #f0f9ff; font-weight: bold; color: #0369a1; }
        .score { text-align: center; font-weight: bold; }
        .pts { text-align: center; color: #0284c7; }
        .played { background: #f0fdf4; }
        footer { margin-top: 20px; font-size: 9px; color: #94a3b8; text-align: center; }
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

    @foreach ($fixtures->groupBy('group_name') as $group => $groupFixtures)
        <table>
            <thead>
                <tr>
                    <th colspan="6" class="group">Grupo {{ $group }}</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Local</th>
                    <th>Pronóstico</th>
                    <th>Visitante</th>
                    <th>Pts</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupFixtures as $fixture)
                    @php
                        $prediction = $predictions[$fixture->id] ?? null;
                        $rowClass = $fixture->is_played ? 'played' : '';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $fixture->match_number }}</td>
                        <td>{{ $fixture->match_date?->format('d/m') }}</td>
                        <td>{{ $fixture->home_team }}</td>
                        <td class="score">
                            {{ $prediction?->home_score ?? '—' }} - {{ $prediction?->away_score ?? '—' }}
                            @if ($fixture->is_played)
                                <br><small style="color:#64748b;">Real: {{ $fixture->home_score }}-{{ $fixture->away_score }}</small>
                            @endif
                        </td>
                        <td>{{ $fixture->away_team }}</td>
                        <td class="pts">{{ $prediction?->points_earned ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <footer>Kinela Mundial FIFA 2026 — Generado {{ now()->format('d/m/Y H:i') }}</footer>
</body>
</html>
