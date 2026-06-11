<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="name" class="wc-label">Nombre del participante</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $participant->name ?? '') }}"
            required
            class="wc-input"
        >
    </div>
    <div>
        <label for="email" class="wc-label">Correo electrónico</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $participant->email ?? '') }}"
            required
            placeholder="correo@ejemplo.com"
            class="wc-input"
        >
        <p class="text-xs text-slate-500 mt-1">Para recibir actualizaciones del ranking</p>
    </div>
</div>

<h2 class="wc-heading mb-4">Pronósticos (72 partidos)</h2>

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
                    </tr>
                </thead>
                <tbody class="wc-table-body">
                    @foreach ($groupFixtures as $fixture)
                        @php
                            $prediction = $predictions[$fixture->id] ?? null;
                            $homeVal = old("predictions.{$fixture->id}.home_score", $prediction?->home_score ?? 0);
                            $awayVal = old("predictions.{$fixture->id}.away_score", $prediction?->away_score ?? 0);
                        @endphp
                        <tr class="wc-table-row">
                            <td class="text-slate-400 font-medium">{{ $fixture->match_number }}</td>
                            <td class="wc-date">{{ $fixture->match_date?->format('d/m') }}</td>
                            <td class="font-medium">{{ $fixture->home_team }}</td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <input
                                        type="number"
                                        name="predictions[{{ $fixture->id }}][home_score]"
                                        value="{{ $homeVal }}"
                                        min="0"
                                        max="20"
                                        required
                                        class="wc-input-sm"
                                    >
                                    <span class="wc-separator">-</span>
                                    <input
                                        type="number"
                                        name="predictions[{{ $fixture->id }}][away_score]"
                                        value="{{ $awayVal }}"
                                        min="0"
                                        max="20"
                                        required
                                        class="wc-input-sm"
                                    >
                                </div>
                            </td>
                            <td class="font-medium">{{ $fixture->away_team }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
