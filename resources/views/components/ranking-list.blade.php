@props(['participants'])

@php
    $total = $participants->count();
@endphp

@if ($total === 0)
    <div class="wc-card text-center py-16">
        <p class="text-lg text-slate-500">Aún no hay participantes registrados.</p>
    </div>
@else
    @if ($total >= 3)
        <div class="ranking-podium">
            @foreach ([1 => 2, 0 => 1, 2 => 3] as $idx => $podiumNum)
                @php $participant = $participants[$idx]; @endphp
                <div class="ranking-podium-card ranking-podium-{{ $podiumNum }}">
                    @if ($podiumNum === 1)
                        <span class="ranking-crown" title="¡Líder!">👑</span>
                    @endif
                    <p class="text-xs font-bold uppercase tracking-wider opacity-70">{{ $podiumNum }}° lugar</p>
                    <p class="text-lg font-bold text-slate-800 mt-1 truncate">
                        <a href="{{ route('participants.show', $participant) }}" class="hover:text-sky-600 transition-colors">
                            {{ $participant->name }}
                        </a>
                    </p>
                    <p class="text-3xl font-black text-slate-800 mt-2">{{ $participant->total_points }}</p>
                    <p class="text-xs text-slate-600">puntos</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($participants as $index => $participant)
            @php $isFirst = $index === 0; @endphp
            <div class="ranking-row ranking-row-default">
                <div class="ranking-pos {{ $isFirst ? 'bg-amber-200 text-amber-800' : 'bg-sky-100 text-sky-700' }}">
                    @if ($isFirst)
                        <span class="text-lg">👑</span>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('participants.show', $participant) }}" class="font-bold text-slate-800 truncate block hover:text-sky-600 transition-colors">
                        {{ $participant->name }}
                    </a>
                    @if ($isFirst)
                        <p class="text-xs text-amber-600 font-medium">Rey de la kinela</p>
                    @else
                        <p class="text-xs text-slate-400">Ver kinela →</p>
                    @endif
                </div>
                <div class="text-right shrink-0">
                    <span class="text-2xl font-black {{ $isFirst ? 'text-amber-500' : 'text-sky-600' }}">
                        {{ $participant->total_points }}
                    </span>
                    <p class="text-xs text-slate-400">pts</p>
                </div>
            </div>
        @endforeach
    </div>
@endif
