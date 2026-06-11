<x-mail::message>
<div style="text-align: center; margin-bottom: 24px;">
<img src="{{ url('logito.png') }}" alt="{{ config('app.name') }}" style="max-height: 72px; max-width: 150px; height: auto; width: auto;">
</div>

# Hola, {{ $participant->name }}

Aquí están tus **72 pronósticos** de la Kinela Mundial FIFA 2026.

## Tu posición actual

**#{{ $position }}** de {{ $totalParticipants }} participantes — **{{ $participant->total_points }} puntos**

<x-mail::panel>
Marcador exacto = 3 pts · Ganador o empate = 1 pt
</x-mail::panel>

@foreach ($fixtures->groupBy('group_name') as $group => $groupFixtures)
### Grupo {{ $group }}

@foreach ($groupFixtures as $fixture)
@php $prediction = $participant->predictions->firstWhere('fixture_id', $fixture->id); @endphp
**#{{ $fixture->match_number }}** {{ $fixture->home_team }} **{{ $prediction?->home_score ?? 0 }}-{{ $prediction?->away_score ?? 0 }}** {{ $fixture->away_team }}
@endforeach

@endforeach

<x-mail::button :url="route('participants.show', $participant)">
Ver tu kinela en la web
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
