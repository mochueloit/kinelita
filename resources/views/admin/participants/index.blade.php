@extends('layouts.app')

@section('title', 'Participantes')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <h1 class="wc-title !text-2xl md:!text-3xl">Participantes</h1>
    <div class="flex flex-wrap gap-3">
        <form method="POST" action="{{ route('admin.notifications.ranking') }}">
            @csrf
            <button type="submit" class="wc-btn-green" onclick="return confirm('¿Enviar notificaciones de ranking a todos los participantes con correo?')">
                Enviar ranking por correo
            </button>
        </form>
        <a href="{{ route('admin.participants.create') }}" class="wc-btn-gold">
            + Nuevo participante
        </a>
    </div>
</div>

@if ($participants->isEmpty())
    <div class="wc-card p-8 text-center text-slate-600">No hay participantes registrados.</div>
@else
    <div class="wc-table-wrap">
        <table class="w-full text-left">
            <thead class="wc-table-head">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th class="text-right">Puntos</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="wc-table-body">
                @foreach ($participants as $participant)
                    <tr class="wc-table-row">
                        <td class="font-semibold text-slate-700">{{ $participant->name }}</td>
                        <td class="text-slate-600 text-sm">{{ $participant->email ?? '—' }}</td>
                        <td class="text-right"><span class="wc-points text-xl">{{ $participant->total_points }}</span></td>
                        <td class="text-right">
                            <div class="flex flex-wrap justify-end gap-2 text-sm">
                                <a href="{{ route('participants.show', $participant) }}" class="wc-link" title="Ver kinela pública">Ver</a>
                                <a href="{{ route('admin.participants.pdf', $participant) }}" class="wc-link" title="Descargar PDF">PDF</a>
                                @if ($participant->email)
                                    <form method="POST" action="{{ route('admin.participants.email-predictions', $participant) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="wc-link" onclick="return confirm('¿Enviar kinela en PDF por correo a {{ $participant->email }}?')">
                                            Email
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400" title="Sin correo registrado">Email</span>
                                @endif
                                <a href="{{ route('admin.participants.edit', $participant) }}" class="wc-link">Editar</a>
                                <form method="POST" action="{{ route('admin.participants.destroy', $participant) }}" class="inline" onsubmit="return confirm('¿Eliminar este participante?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<div class="mt-6">
    <a href="{{ route('admin.dashboard') }}" class="wc-link-back">← Volver al panel</a>
</div>
@endsection
