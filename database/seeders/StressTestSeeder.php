<?php

namespace Database\Seeders;

use App\Models\Fixture;
use App\Models\Participant;
use App\Models\Prediction;
use App\Models\RankingComment;
use App\Services\KinelaScoringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StressTestSeeder extends Seeder
{
    private const PARTICIPANTS = 50;

    private const COMMENTS = 50_000;

    public function run(): void
    {
        $this->command?->info('Limpiando datos...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Prediction::query()->truncate();
        Participant::query()->truncate();
        RankingComment::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Fixture::query()->update([
            'home_score' => null,
            'away_score' => null,
            'is_played' => false,
        ]);

        $this->command?->info('Creando '.self::PARTICIPANTS.' participantes con pronósticos...');
        $this->seedParticipants();

        $this->command?->info('Cargando resultados de partidos...');
        $this->seedFixtureResults();

        app(KinelaScoringService::class)->recalculateAll();

        $this->command?->info('Insertando '.number_format(self::COMMENTS).' comentarios...');
        $this->seedComments();

        $this->command?->info('Listo.');
    }

    private function seedParticipants(): void
    {
        $fixtures = Fixture::orderBy('match_number')->get();
        $faker = fake('es_ES');

        for ($i = 1; $i <= self::PARTICIPANTS; $i++) {
            $participant = Participant::create([
                'name' => $faker->unique()->name(),
                'email' => "participante{$i}@kinela.test",
            ]);

            $predictions = [];

            foreach ($fixtures as $fixture) {
                $predictions[] = [
                    'participant_id' => $participant->id,
                    'fixture_id' => $fixture->id,
                    'home_score' => random_int(0, 4),
                    'away_score' => random_int(0, 4),
                    'points_earned' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach (array_chunk($predictions, 72) as $chunk) {
                Prediction::insert($chunk);
            }
        }
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

    private function seedComments(): void
    {
        $batch = [];
        $batchSize = 1000;
        $phrases = [
            '¡Ese va remontando!',
            'Kinela nivel Dios hoy.',
            'Con ese marcador me retiro.',
            'El líder está intratable.',
            'Necesito que pierda México ya.',
            'Qué barbaridad este ranking.',
            'Mañana cambia todo, confío.',
            'Esto está más apretado que final.',
            'Mi pronóstico era oro puro.',
            'Al último le falta suerte nada más.',
        ];

        for ($i = 1; $i <= self::COMMENTS; $i++) {
            $batch[] = [
                'name' => $i % 3 === 0 ? null : 'Fan #'.$i,
                'email' => "fan{$i}@kinela.test",
                'comment' => $phrases[$i % count($phrases)].' (#'.$i.')',
                'created_at' => now()->subMinutes(self::COMMENTS - $i),
                'updated_at' => now()->subMinutes(self::COMMENTS - $i),
            ];

            if (count($batch) >= $batchSize) {
                RankingComment::insert($batch);
                $batch = [];

                if ($i % 10_000 === 0) {
                    $this->command?->info('  → '.number_format($i).' / '.number_format(self::COMMENTS));
                }
            }
        }

        if ($batch !== []) {
            RankingComment::insert($batch);
        }
    }
}
