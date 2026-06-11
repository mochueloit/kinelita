<?php

namespace Database\Seeders;

use App\Models\Fixture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MillionStressTestSeeder extends Seeder
{
    private const PARTICIPANTS = 1_000_000;

    private const PARTICIPANT_BATCH = 10_000;

    private const SCORE_BATCH = 50_000;

    public function run(): void
    {
        ini_set('memory_limit', '512M');

        $this->command?->info('Limpiando base de datos...');
        $this->cleanTables();

        $this->command?->info('Aplicando resultados a los 72 partidos...');
        $this->seedFixtureResults();

        $this->command?->info('Insertando '.number_format(self::PARTICIPANTS).' participantes...');
        $this->seedParticipants();

        $this->command?->info('Generando pronósticos (72 por participante)...');
        $this->seedPredictions();

        $this->command?->info('Calculando puntos por partido...');
        $this->recalculatePoints();

        $this->command?->info('Sumando totales del ranking...');
        $this->recalculateTotals();

        $this->command?->info('Listo.');
        $this->printStats();
    }

    private function cleanTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('predictions')->truncate();
        DB::table('participants')->truncate();
        DB::table('ranking_comments')->truncate();
        DB::table('activity_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Fixture::query()->update([
            'home_score' => null,
            'away_score' => null,
            'is_played' => false,
        ]);
    }

    private function seedFixtureResults(): void
    {
        Fixture::query()->each(function (Fixture $fixture) {
            $fixture->update([
                'home_score' => random_int(0, 4),
                'away_score' => random_int(0, 4),
                'is_played' => true,
            ]);
        });
    }

    private function seedParticipants(): void
    {
        $now = now()->toDateTimeString();

        for ($start = 1; $start <= self::PARTICIPANTS; $start += self::PARTICIPANT_BATCH) {
            $end = min($start + self::PARTICIPANT_BATCH - 1, self::PARTICIPANTS);
            $rows = [];

            for ($i = $start; $i <= $end; $i++) {
                $rows[] = [
                    'name' => "Participante {$i}",
                    'email' => "p{$i}@kinela.test",
                    'total_points' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('participants')->insert($rows);

            if ($end % 100_000 === 0 || $end === self::PARTICIPANTS) {
                $this->command?->info('  → participantes: '.number_format($end).' / '.number_format(self::PARTICIPANTS));
            }
        }
    }

    private function seedPredictions(): void
    {
        $now = now()->toDateTimeString();
        $maxId = (int) DB::table('participants')->max('id');

        for ($start = 1; $start <= $maxId; $start += self::PARTICIPANT_BATCH) {
            $end = min($start + self::PARTICIPANT_BATCH - 1, $maxId);

            DB::insert("
                INSERT INTO predictions (participant_id, fixture_id, home_score, away_score, points_earned, created_at, updated_at)
                SELECT
                    p.id,
                    f.id,
                    FLOOR(RAND() * 5),
                    FLOOR(RAND() * 5),
                    0,
                    ?,
                    ?
                FROM participants p
                CROSS JOIN fixtures f
                WHERE p.id BETWEEN ? AND ?
            ", [$now, $now, $start, $end]);

            if ($end % 100_000 === 0 || $end === $maxId) {
                $this->command?->info('  → pronósticos hasta participante #'.number_format($end));
            }
        }
    }

    private function recalculatePoints(): void
    {
        $maxId = (int) DB::table('participants')->max('id');

        for ($start = 1; $start <= $maxId; $start += self::SCORE_BATCH) {
            $end = min($start + self::SCORE_BATCH - 1, $maxId);

            DB::update("
                UPDATE predictions pr
                INNER JOIN fixtures f ON f.id = pr.fixture_id AND f.is_played = 1
                SET pr.points_earned = CASE
                    WHEN pr.home_score = f.home_score AND pr.away_score = f.away_score THEN 3
                    WHEN (
                        (pr.home_score > pr.away_score AND f.home_score > f.away_score)
                        OR (pr.home_score < pr.away_score AND f.home_score < f.away_score)
                        OR (pr.home_score = pr.away_score AND f.home_score = f.away_score)
                    ) THEN 1
                    ELSE 0
                END
                WHERE pr.participant_id BETWEEN ? AND ?
            ", [$start, $end]);

            if ($end % 200_000 === 0 || $end === $maxId) {
                $this->command?->info('  → puntos calculados hasta #'.number_format($end));
            }
        }
    }

    private function recalculateTotals(): void
    {
        $maxId = (int) DB::table('participants')->max('id');

        for ($start = 1; $start <= $maxId; $start += self::SCORE_BATCH) {
            $end = min($start + self::SCORE_BATCH - 1, $maxId);

            DB::update("
                UPDATE participants p
                INNER JOIN (
                    SELECT participant_id, SUM(points_earned) AS total
                    FROM predictions
                    WHERE participant_id BETWEEN ? AND ?
                    GROUP BY participant_id
                ) sums ON sums.participant_id = p.id
                SET p.total_points = sums.total
                WHERE p.id BETWEEN ? AND ?
            ", [$start, $end, $start, $end]);

            if ($end % 200_000 === 0 || $end === $maxId) {
                $this->command?->info('  → totales hasta #'.number_format($end));
            }
        }
    }

    private function printStats(): void
    {
        $this->command?->table(
            ['Tabla', 'Registros'],
            [
                ['participants', number_format(DB::table('participants')->count())],
                ['predictions', number_format(DB::table('predictions')->count())],
                ['fixtures jugados', DB::table('fixtures')->where('is_played', true)->count()],
            ],
        );
    }
}
