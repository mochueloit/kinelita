@props(['fixtures', 'predictions', 'readonly' => true])

@foreach ($fixtures->groupBy('group_name') as $group => $groupFixtures)
    <div class="mb-8">
        <h3 class="wc-group-badge mb-3">Grupo {{ $group }}</h3>
        <div class="wc-table-wrap">
            <table class="w-full text-sm">
                <thead class="wc-table-head">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Local</th>
                        <th class="text-center">Pronóstico</th>
                        <th>Visitante</th>
                        @if ($readonly)
                            <th class="text-center">Pts</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="wc-table-body">
                    @foreach ($groupFixtures as $fixture)
                        @php $prediction = $predictions[$fixture->id] ?? null; @endphp
                        <tr class="{{ $fixture->is_played ? 'wc-played-row' : 'wc-table-row' }}">
                            <td class="text-slate-400 font-medium">{{ $fixture->match_number }}</td>
                            <td class="wc-date">{{ $fixture->match_date?->format('d/m') }}</td>
                            <td class="font-medium">{{ $fixture->home_team }}</td>
                            <td class="text-center">
                                <span class="font-bold text-sky-700">
                                    {{ $prediction?->home_score ?? '—' }} - {{ $prediction?->away_score ?? '—' }}
                                </span>
                                @if ($fixture->is_played)
                                    <p class="text-xs text-emerald-600 mt-0.5">
                                        Real: {{ $fixture->home_score }}-{{ $fixture->away_score }}
                                    </p>
                                @endif
                            </td>
                            <td class="font-medium">{{ $fixture->away_team }}</td>
                            @if ($readonly)
                                <td class="text-center">
                                    @if ($fixture->is_played)
                                        <span class="wc-points">{{ $prediction?->points_earned ?? 0 }}</span>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
