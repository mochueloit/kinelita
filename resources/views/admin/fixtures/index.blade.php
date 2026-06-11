@extends('layouts.app')

@section('title', 'Resultados')

@section('content')
<div class="mb-8">
    <h1 class="wc-title !text-2xl md:!text-3xl">Resultados de Partidos</h1>
    <p class="wc-subtitle mt-2 text-left">Al guardar, el sistema calcula los puntos y actualiza el ranking automáticamente.</p>
</div>

<form method="POST" action="{{ route('admin.fixtures.update') }}">
    @csrf
    @method('PUT')

    @foreach ($fixtures as $group => $groupFixtures)
        <div class="mb-8">
            <h2 class="wc-group-badge mb-3">Grupo {{ $group }}</h2>
            <div class="wc-table-wrap">
                <table class="w-full text-sm">
                    <thead class="wc-table-head">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Local</th>
                            <th class="text-center">Resultado</th>
                            <th>Visitante</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="wc-table-body">
                        @foreach ($groupFixtures as $fixture)
                            <tr class="{{ $fixture->is_played ? 'wc-played-row' : 'wc-table-row' }}">
                                <td class="text-slate-400 font-medium">{{ $fixture->match_number }}</td>
                                <td class="wc-date">{{ $fixture->match_date?->format('d/m') }}</td>
                                <td class="font-medium">{{ $fixture->home_team }}</td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <input
                                            type="number"
                                            name="fixtures[{{ $fixture->id }}][home_score]"
                                            value="{{ old("fixtures.{$fixture->id}.home_score", $fixture->home_score) }}"
                                            min="0"
                                            max="20"
                                            placeholder="-"
                                            class="wc-input-sm"
                                        >
                                        <span class="wc-separator">-</span>
                                        <input
                                            type="number"
                                            name="fixtures[{{ $fixture->id }}][away_score]"
                                            value="{{ old("fixtures.{$fixture->id}.away_score", $fixture->away_score) }}"
                                            min="0"
                                            max="20"
                                            placeholder="-"
                                            class="wc-input-sm"
                                        >
                                    </div>
                                </td>
                                <td class="font-medium">{{ $fixture->away_team }}</td>
                                <td class="text-center">
                                    @if ($fixture->is_played)
                                        <span class="wc-badge-played">Jugado</span>
                                    @else
                                        <span class="wc-badge-pending">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="sticky bottom-4 wc-card p-4 space-y-4">
        <label class="flex items-center gap-3 text-slate-600 font-medium cursor-pointer">
            <input
                type="checkbox"
                name="notify_participants"
                value="1"
                checked
                class="rounded border-sky-300 text-sky-500 w-5 h-5"
            >
            Enviar notificaciones de ranking por correo al guardar (en cola, lotes de {{ config('kinela.notification_batch_size', 5) }})
        </label>
        <div class="flex gap-4">
            <button type="submit" class="wc-btn-gold py-3 px-6">
                Guardar resultados y calcular puntos
            </button>
            <a href="{{ route('admin.dashboard') }}" class="wc-btn-green py-3 px-6">
                Volver al panel
            </a>
        </div>
    </div>
</form>
@endsection
