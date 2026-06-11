@extends('layouts.app')

@section('title', $participant->name)

@section('content')
<div class="ranking-layout">
    <div class="ranking-main">
        <div class="mb-6">
            <a href="{{ route('ranking') }}" class="wc-link-back">← Volver al ranking</a>
        </div>

        <div class="wc-card p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-sky-600">Kinela de</p>
                    <h1 class="wc-title !text-2xl md:!text-3xl !mb-1 mt-1">{{ $participant->name }}</h1>
                    @if ($position)
                        <p class="text-slate-500 text-sm">Posición #{{ $position }} en el ranking</p>
                    @endif
                    <p class="text-slate-400 text-xs mt-1">{{ $playedCount }} de {{ $totalFixtures }} partidos contabilizados</p>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-black text-sky-600">{{ $participant->total_points }}</p>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">puntos</p>
                </div>
            </div>
        </div>

        <h2 class="wc-heading mb-2">Pronósticos</h2>
        <p class="text-slate-500 text-sm mb-4">Solo lectura — no se pueden modificar desde aquí.</p>

        <x-predictions-table :fixtures="$fixtures" :predictions="$predictions" />
    </div>

    <x-participant-comments :participant="$participant" :comments="$comments" class="ranking-comments" />
</div>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('comentarios');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    </script>
@endif
@endsection
