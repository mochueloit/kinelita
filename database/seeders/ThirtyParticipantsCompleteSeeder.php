<?php

namespace Database\Seeders;

use App\Models\Fixture;
use App\Models\Participant;
use App\Models\Prediction;
use App\Models\RankingComment;
use App\Services\KinelaScoringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 30 participantes demo + 24 partidos con resultado oficial.
 */
class ThirtyParticipantsCompleteSeeder extends Seeder
{
    private const PARTICIPANTS = 30;

    private const OFFICIAL_PLAYED_MATCHES = 24;

    private const WINNER_NAME = 'Carlos Mendoza';

    /** Resultados simulados de los 72 partidos (local, visitante). */
    private const ALL_RESULTS = [
        1  => [2, 0],  2  => [1, 1],  3  => [2, 1],  4  => [1, 2],
        5  => [0, 2],  6  => [3, 1],  7  => [0, 2],  8  => [1, 1],
        9  => [4, 0],  10 => [2, 1],  11 => [1, 2],  12 => [2, 0],
        13 => [2, 0],  14 => [1, 1],  15 => [0, 3],  16 => [2, 2],
        17 => [3, 1],  18 => [0, 2],  19 => [2, 0],  20 => [1, 0],
        21 => [3, 0],  22 => [2, 2],  23 => [1, 0],  24 => [0, 2],
        25 => [1, 1],  26 => [2, 1],  27 => [3, 0],  28 => [2, 1],
        29 => [1, 1],  30 => [0, 2],  31 => [4, 0],  32 => [1, 2],
        33 => [2, 0],  34 => [3, 1],  35 => [2, 0],  36 => [0, 1],
        37 => [2, 0],  38 => [1, 1],  39 => [3, 0],  40 => [1, 2],
        41 => [2, 1],  42 => [4, 0],  43 => [1, 1],  44 => [0, 0],
        45 => [3, 0],  46 => [2, 0],  47 => [0, 3],  48 => [2, 1],
        49 => [1, 0],  50 => [2, 2],  51 => [0, 3],  52 => [2, 0],
        53 => [1, 2],  54 => [0, 1],  55 => [0, 2],  56 => [1, 3],
        57 => [2, 1],  58 => [0, 2],  59 => [1, 1],  60 => [2, 0],
        61 => [0, 2],  62 => [3, 0],  63 => [1, 1],  64 => [0, 2],
        65 => [0, 1],  66 => [1, 4],  67 => [0, 3],  68 => [2, 1],
        69 => [1, 2],  70 => [1, 0],  71 => [1, 1],  72 => [0, 3],
    ];

    private const NAMES = [
        'Carlos Mendoza', 'Ana Rodríguez', 'Pedro Gutiérrez', 'María Fernández',
        'Ricardo Salazar', 'Laura Jiménez', 'Fernando Castro', 'Patricia Ruiz',
        'Diego Herrera', 'Carmen Vargas', 'Andrés Morales', 'Sofía Delgado',
        'Miguel Ríos', 'Valentina Peña', 'Gabriel Ortiz', 'Daniela Campos',
        'Héctor Luna', 'Isabel Moreno', 'Javier Acosta', 'Lucía Paredes',
        'Oscar Fuentes', 'Natalia Reyes', 'Tomás Aguirre', 'Paula Medina',
        'Sergio Navarro', 'Claudia Silva', 'Raúl Domínguez', 'Elena Torres',
        'Iván Ramírez', 'Beatriz Soto',
    ];

    public function run(): void
    {
        $fixtures = Fixture::orderBy('match_number')->get();

        if ($fixtures->isEmpty()) {
            $this->command?->error('No hay partidos. Ejecuta primero: php artisan db:seed --class=FixtureSeeder');

            return;
        }

        if ($fixtures->count() !== 72) {
            $this->command?->warn("Se encontraron {$fixtures->count()} partidos (se esperaban 72).");
        }

        DB::transaction(function () use ($fixtures) {
            $this->command?->info('Limpiando participantes y pronósticos demo...');

            Prediction::query()->delete();
            Participant::query()->delete();
            RankingComment::query()->delete();

            $this->applyOfficialResults($fixtures);
            $resultsByMatch = self::ALL_RESULTS;

            for ($i = 0; $i < self::PARTICIPANTS; $i++) {
                $participant = Participant::create([
                    'name' => self::NAMES[$i],
                    'email' => 'demo'.($i + 1).'@kinela.test',
                ]);

                $profile = $i % 6;
                $predictions = [];

                foreach ($fixtures as $fixture) {
                    $actual = $resultsByMatch[$fixture->match_number] ?? [1, 1];

                    [$home, $away] = $this->predictForProfile($profile, $fixture->match_number, $actual);

                    $predictions[] = [
                        'participant_id' => $participant->id,
                        'fixture_id' => $fixture->id,
                        'home_score' => $home,
                        'away_score' => $away,
                        'points_earned' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                foreach (array_chunk($predictions, 72) as $chunk) {
                    Prediction::insert($chunk);
                }
            }

            app(KinelaScoringService::class)->recalculateAll();
        });

        $winner = Participant::query()
            ->orderByDesc('total_points')
            ->orderBy('name')
            ->first();

        $this->command?->info(self::PARTICIPANTS.' participantes creados.');
        $this->command?->info(self::OFFICIAL_PLAYED_MATCHES.' partidos con resultado cargado.');
        $this->command?->info("Líder: {$winner?->name} — {$winner?->total_points} pts");

        if ($winner && $winner->name !== self::WINNER_NAME) {
            $this->command?->warn('Nota: se esperaba '.self::WINNER_NAME.' como líder oficial parcial.');
        }
    }

    private function applyOfficialResults($fixtures): void
    {
        Fixture::query()->update([
            'home_score' => null,
            'away_score' => null,
            'is_played' => false,
        ]);

        foreach ($fixtures as $fixture) {
            if ($fixture->match_number > self::OFFICIAL_PLAYED_MATCHES) {
                continue;
            }

            [$home, $away] = self::ALL_RESULTS[$fixture->match_number] ?? [1, 0];

            Fixture::query()
                ->where('id', $fixture->id)
                ->update([
                    'home_score' => $home,
                    'away_score' => $away,
                    'is_played' => true,
                ]);
        }
    }

    /**
     * Perfiles de pronóstico (se repiten cada 6 participantes):
     * 0 = exacto en todo → ganador (216 pts)
     * 1 = acierta resultado, no marcador (~72 pts)
     * 2 = favorece local
     * 3 = muchos empates
     * 4 = goleadas
     * 5 = patrón fijo variado
     *
     * @param  array{0: int, 1: int}  $actual
     * @return array{0: int, 1: int}
     */
    private function predictForProfile(int $profile, int $matchNumber, array $actual): array
    {
        return match ($profile) {
            0 => $actual,
            1 => $this->sameOutcomeDifferentScore($actual),
            2 => [random_int(2, 3), random_int(0, 1)],
            3 => [($n = ($matchNumber % 4)), $n],
            4 => [random_int(3, 5), random_int(1, 3)],
            5 => [($matchNumber % 3), (($matchNumber + 1) % 3)],
            default => [1, 1],
        };
    }

    /** @param  array{0: int, 1: int}  $actual */
    private function sameOutcomeDifferentScore(array $actual): array
    {
        [$home, $away] = $actual;

        if ($home === $away) {
            return [$home + 1, $away + 1];
        }

        if ($home > $away) {
            return [max(0, $home - 1), $away + 1];
        }

        return [$home + 1, max(0, $away - 1)];
    }
}
