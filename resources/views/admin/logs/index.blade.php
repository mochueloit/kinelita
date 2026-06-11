@extends('layouts.app')

@section('title', 'Logs de actividad')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="wc-title !text-2xl md:!text-3xl">Logs de actividad</h1>
    <a href="{{ route('admin.dashboard') }}" class="wc-link-back">← Volver al panel</a>
</div>

<form method="GET" class="wc-card p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Buscar en descripción o datos..."
        class="wc-input flex-1"
    >
    <select name="action" class="wc-input sm:max-w-xs">
        <option value="">Todas las acciones</option>
        @foreach ($actions as $action)
            <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
        @endforeach
    </select>
    <button type="submit" class="wc-btn-gold shrink-0">Filtrar</button>
</form>

<div class="wc-table-wrap">
    <table class="w-full text-left text-sm">
        <thead class="wc-table-head">
            <tr>
                <th class="w-40">Fecha</th>
                <th class="w-44">Acción</th>
                <th>Descripción</th>
                <th class="w-32">Usuario</th>
                <th class="w-28">IP</th>
            </tr>
        </thead>
        <tbody class="wc-table-body">
            @forelse ($logs as $log)
                <tr class="wc-table-row align-top">
                    <td class="text-slate-500 whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <span class="inline-block px-2 py-0.5 rounded-full bg-sky-100 text-sky-700 text-xs font-medium">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td>
                        <p class="text-slate-700">{{ $log->description }}</p>
                        @if ($log->properties)
                            <pre class="mt-2 text-xs text-slate-500 bg-slate-50 rounded-lg p-2 overflow-x-auto max-w-xl">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @endif
                    </td>
                    <td class="text-slate-600">{{ $log->user?->name ?? '—' }}</td>
                    <td class="text-slate-400 text-xs">{{ $log->ip_address ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-slate-500">No hay registros aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $logs->links() }}
</div>
@endsection
