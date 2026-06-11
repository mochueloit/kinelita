<x-mail::message>
<div style="text-align: center; margin-bottom: 24px;">
<img src="{{ url('logito.png') }}" alt="{{ config('app.name') }}" style="max-height: 72px; max-width: 150px; height: auto; width: auto;">
</div>

# Hola, {{ $participant->name }}

Aquí va tu resumen del ranking de la **Kinela Mundial FIFA 2026**.

## Tu posición

**#{{ $position }}** de {{ $totalParticipants }} participantes — **{{ $participant->total_points }} puntos**

<x-mail::panel>
Marcador exacto = 3 pts · Ganador o empate = 1 pt
</x-mail::panel>

## Top 5 actual

@foreach ($topRanking as $index => $player)
{{ $index + 1 }}. **{{ $player->name }}** — {{ $player->total_points }} pts
@endforeach

<x-mail::button :url="route('ranking')">
Ver ranking completo
</x-mail::button>

¡Sigue pronosticando y sube en la tabla!

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
