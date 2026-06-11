<?php

namespace Database\Seeders;

use App\Models\Fixture;
use App\Models\Participant;
use App\Models\Prediction;
use App\Services\KinelaScoringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Prediction::query()->delete();
            Participant::query()->delete();

            Fixture::query()->update([
                'home_score' => null,
                'away_score' => null,
                'is_played' => false,
            ]);

            $fixtures = Fixture::orderBy('match_number')->get();
            $faker = fake('es_ES');

            $participants = collect();

            for ($i = 1; $i <= 50; $i++) {
                $participants->push(Participant::create([
                    'name' => $faker->unique()->name(),
                    'email' => "participante{$i}@kinela.test",
                ]));
            }

            foreach ($participants as $participant) {
                foreach ($fixtures as $fixture) {
                    Prediction::create([
                        'participant_id' => $participant->id,
                        'fixture_id' => $fixture->id,
                        'home_score' => random_int(0, 4),
                        'away_score' => random_int(0, 4),
                    ]);
                }
            }

            foreach ($fixtures as $fixture) {
                $fixture->update([
                    'home_score' => random_int(0, 4),
                    'away_score' => random_int(0, 4),
                    'is_played' => true,
                ]);
            }

            app(KinelaScoringService::class)->recalculateAll();
        });
    }
}
