@extends('layouts.app')

@section('title', 'Panel Admin')

@section('content')
<x-page-header title="Panel de Administración" :show-logo="false" :centered="false" class="!mb-6" />

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="wc-card p-6 text-center">
        <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Participantes</p>
        <p class="wc-stat-value mt-2">{{ $participantsCount }}</p>
    </div>
    <div class="wc-card p-6 text-center">
        <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Partidos jugados</p>
        <p class="wc-stat-value mt-2">{{ $fixturesPlayed }} <span class="text-lg text-slate-400">/ {{ $totalFixtures }}</span></p>
    </div>
    <div class="wc-card p-6 text-center">
        <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Pendientes</p>
        <p class="wc-stat-value-gold mt-2">{{ $totalFixtures - $fixturesPlayed }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 lg:grid-cols-2">
    <a href="{{ route('admin.participants.index') }}" class="wc-card wc-card-accent-sky p-6 hover:shadow-md transition group">
        <h2 class="wc-heading group-hover:text-sky-600">Participantes</h2>
        <p class="text-slate-500 mt-2">Registrar y editar pronósticos de cada jugador</p>
    </a>
    <a href="{{ route('admin.fixtures.index') }}" class="wc-card wc-card-accent-rose p-6 hover:shadow-md transition group">
        <h2 class="wc-heading group-hover:text-sky-600">Resultados</h2>
        <p class="text-slate-500 mt-2">Cargar marcadores y calcular puntos automáticamente</p>
    </a>
    <a href="{{ route('admin.logs.index') }}" class="wc-card wc-card-accent-sky p-6 hover:shadow-md transition group md:col-span-2">
        <h2 class="wc-heading group-hover:text-sky-600">Logs de actividad</h2>
        <p class="text-slate-500 mt-2">Historial de registros, puntajes y cambios para depuración</p>
    </a>
</div>

<div class="wc-card wc-card-accent-sky p-6">
    <h2 class="wc-heading">Notificaciones de ranking</h2>
    <p class="text-slate-500 mt-2">
        {{ $participantsWithEmail }} de {{ $participantsCount }} participantes tienen correo registrado.
        Los envíos se procesan en cola, en lotes de {{ config('kinela.notification_batch_size', 5) }}
        cada {{ config('kinela.notification_batch_delay', 15) }} segundos.
    </p>
    <form method="POST" action="{{ route('admin.notifications.ranking') }}" class="mt-4">
        @csrf
        <button type="submit" class="wc-btn-gold" onclick="return confirm('¿Enviar notificaciones de ranking a todos los participantes con correo?')">
            Enviar ranking por correo
        </button>
    </form>
</div>
@endsection
