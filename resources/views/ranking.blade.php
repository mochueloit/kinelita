@extends('layouts.app')

@section('title', 'Ranking')

@section('content')
@php
    $total = $participants->count();
@endphp

<div class="mb-6">
    <h1 class="wc-title !text-2xl md:!text-3xl">Ranking</h1>
    <p class="text-slate-500 text-sm mt-1">
        {{ $playedCount }} de {{ $totalFixtures }} partidos con resultado cargado.
        Haz clic en un nombre para ver su kinela.
    </p>
</div>

<div class="ranking-layout">
    <div class="ranking-main">
        <x-ranking-list :participants="$participants" />
    </div>

    <x-ranking-comments :comments="$comments" class="ranking-comments" />
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
