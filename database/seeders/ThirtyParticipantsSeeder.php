<?php

namespace Database\Seeders;

use App\Models\Fixture;
use App\Models\Participant;
use App\Models\Prediction;
use App\Services\KinelaScoringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThirtyParticipantsSeeder extends Seeder
{
    private const PARTICIPANTS = 30;

    /** Partidos con resultado ya cargado (jornadas 1 y 2 parcial). */
    private const PLAYED_RESULTS = [
        1  => [2, 0],  // México - Sudáfrica
        2  => [1, 1],  // Corea - Rep. Checa
        3  => [2, 1],  // Canadá - Bosnia
        4  => [1, 2],  // EE.UU. - Paraguay
        5  => [0, 2],  // Catar - Suiza
        6  => [3, 1],  // Brasil - Marruecos
        7  => [0, 2],  // Haití - Escocia
        8  => [1, 1],  // Australia - Turquía
        9  => [4, 0],  // Alemania - Curazao
        10 => [2, 1],  // Países Bajos - Japón
        11 => [1, 2],  // Costa de Marfil - Ecuador
        12 => [2, 0],  // Suecia - Túnez
        13 => [2, 0],  // España - Cabo Verde
        14 => [1, 1],  // Bélgica - Egipto
        15 => [0, 3],  // Arabia Saudí - Uruguay
        16 => [2, 2],  // Irán - Nueva Zelanda
        17 => [3, 1],  // Francia - Senegal
        18 => [0, 2],  // Irak - Noruega
        19 => [2, 0],  // Argentina - Argelia
        20 => [1, 0],  // Austria - Jordania
        21 => [3, 0],  // Portugal - RD Congo
        22 => [2, 2],  // Inglaterra - Croacia
        23 => [1, 0],  // Ghana - Panamá
        24 => [0, 2],  // Uzbekistán - Colombia
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

        DB::transaction(function () use ($fixtures) {
            $this->applyPlayedResults($fixtures);

            for ($i = 0; $i < self::PARTICIPANTS; $i++) {
                $participant = Participant::create([
                    'name' => self::NAMES[$i],
                    'email' => 'demo'.($i + 1).'@kinela.test',
                ]);

                $profile = $i % 6;

                foreach ($fixtures as $fixture) {
                    $actual = $fixture->fresh()->is_played
                        ? [$fixture->home_score, $fixture->away_score]
                        : null;

                    [$home, $away] = $this->predictForProfile(
                        $profile,
                        $fixture,
                        $actual,
                    );

                    Prediction::create([
                        'participant_id' => $participant->id,
                        'fixture_id' => $fixture->id,
                        'home_score' => $home,
                        'away_score' => $away,
                    ]);
                }
            }

            app(KinelaScoringService::class)->recalculateAll();
        });

        $this->command?->info(self::PARTICIPANTS.' participantes demo creados con pronósticos variados.');
        $this->command?->info(count(self::PLAYED_RESULTS).' partidos con resultado para generar ranking.');
    }

    private function applyPlayedResults($fixtures): void
    {
        foreach ($fixtures as $fixture) {
            if (! isset(self::PLAYED_RESULTS[$fixture->match_number])) {
                continue;
            }

            if ($fixture->is_played) {
                continue;
            }

            [$home, $away] = self::PLAYED_RESULTS[$fixture->match_number];

            $fixture->update([
                'home_score' => $home,
                'away_score' => $away,
                'is_played' => true,
            ]);
        }
    }

    /**
     * Perfiles:
     * 0 = acierta marcadores exactos en partidos jugados
     * 1 = acierta ganador/empate pero no el marcador
     * 2 = siempre gana el local
     * 3 = muchos empates
     * 4 = partidos goleados
     * 5 = aleatorio conservador
     *
     * @param  array{0: int, 1: int}|null  $actual
     * @return array{0: int, 1: int}
     */
    private function predictForProfile(int $profile, Fixture $fixture, ?array $actual): array
    {
        if ($actual !== null) {
            return match ($profile) {
                0 => $actual,
                1 => $this->sameOutcomeDifferentScore($actual),
                default => $this->predictUnplayed($profile, $fixture->match_number),
            };
        }

        return $this->predictUnplayed($profile, $fixture->match_number);
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

    /** @return array{0: int, 1: int} */
    private function predictUnplayed(int $profile, int $matchNumber): array
    {
        return match ($profile) {
            0 => [random_int(1, 3), random_int(0, 2)],
            1 => [random_int(0, 3), random_int(0, 3)],
            2 => [random_int(2, 4), random_int(0, 1)],
            3 => [($n = random_int(0, 3)), $n],
            4 => [random_int(2, 5), random_int(1, 4)],
            5 => [($matchNumber % 3), (($matchNumber + 1) % 3)],
            default => [1, 1],
        };
    }
}
