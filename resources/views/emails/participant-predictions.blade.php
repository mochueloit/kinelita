<x-mail::message>
<div style="text-align: center; margin-bottom: 24px;">
<img src="{{ url('logito.png') }}" alt="{{ config('app.name') }}" style="max-height: 72px; max-width: 150px; height: auto; width: auto;">
</div>

# Hola, {{ $participant->name }}

Adjuntamos tu **kinela en PDF** con los 72 pronósticos ordenados por fecha del partido.

## Tu posición actual

**#{{ $position }}** de {{ $totalParticipants }} participantes — **{{ $participant->total_points }} puntos**

<x-mail::panel>
Marcador exacto = 3 pts · Ganador o empate = 1 pt
</x-mail::panel>

Abre el archivo PDF adjunto para ver el detalle completo de cada partido, tu pronóstico y los puntos obtenidos.

<x-mail::button :url="route('participants.show', $participant)">
Ver tu kinela en la web
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
