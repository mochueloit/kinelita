<?php

namespace Database\Seeders;

use App\Models\Fixture;
use Illuminate\Database\Seeder;

class FixtureSeeder extends Seeder
{
    public function run(): void
    {
        $fixtures = [
            // Jornada 1
            ['match_number' => 1,  'group_name' => 'A', 'match_date' => '2026-06-11', 'home_team' => 'México',                    'away_team' => 'Sudáfrica'],
            ['match_number' => 2,  'group_name' => 'A', 'match_date' => '2026-06-11', 'home_team' => 'República de Corea',      'away_team' => 'República Checa'],
            ['match_number' => 3,  'group_name' => 'B', 'match_date' => '2026-06-12', 'home_team' => 'Canadá',                  'away_team' => 'Bosnia y Herzegovina'],
            ['match_number' => 4,  'group_name' => 'D', 'match_date' => '2026-06-12', 'home_team' => 'Estados Unidos',          'away_team' => 'Paraguay'],
            ['match_number' => 5,  'group_name' => 'B', 'match_date' => '2026-06-13', 'home_team' => 'Catar',                   'away_team' => 'Suiza'],
            ['match_number' => 6,  'group_name' => 'C', 'match_date' => '2026-06-13', 'home_team' => 'Brasil',                  'away_team' => 'Marruecos'],
            ['match_number' => 7,  'group_name' => 'C', 'match_date' => '2026-06-13', 'home_team' => 'Haití',                   'away_team' => 'Escocia'],
            ['match_number' => 8,  'group_name' => 'D', 'match_date' => '2026-06-13', 'home_team' => 'Australia',               'away_team' => 'Turquía'],
            ['match_number' => 9,  'group_name' => 'E', 'match_date' => '2026-06-14', 'home_team' => 'Alemania',                'away_team' => 'Curazao'],
            ['match_number' => 10, 'group_name' => 'F', 'match_date' => '2026-06-14', 'home_team' => 'Países Bajos',            'away_team' => 'Japón'],
            ['match_number' => 11, 'group_name' => 'E', 'match_date' => '2026-06-14', 'home_team' => 'Costa de Marfil',         'away_team' => 'Ecuador'],
            ['match_number' => 12, 'group_name' => 'F', 'match_date' => '2026-06-14', 'home_team' => 'Suecia',                  'away_team' => 'Túnez'],
            ['match_number' => 13, 'group_name' => 'H', 'match_date' => '2026-06-15', 'home_team' => 'España',                  'away_team' => 'Cabo Verde'],
            ['match_number' => 14, 'group_name' => 'G', 'match_date' => '2026-06-15', 'home_team' => 'Bélgica',                 'away_team' => 'Egipto'],
            ['match_number' => 15, 'group_name' => 'H', 'match_date' => '2026-06-15', 'home_team' => 'Arabia Saudí',            'away_team' => 'Uruguay'],
            ['match_number' => 16, 'group_name' => 'G', 'match_date' => '2026-06-15', 'home_team' => 'Irán',                    'away_team' => 'Nueva Zelanda'],
            ['match_number' => 17, 'group_name' => 'I', 'match_date' => '2026-06-16', 'home_team' => 'Francia',                 'away_team' => 'Senegal'],
            ['match_number' => 18, 'group_name' => 'I', 'match_date' => '2026-06-16', 'home_team' => 'Irak',                    'away_team' => 'Noruega'],
            ['match_number' => 19, 'group_name' => 'J', 'match_date' => '2026-06-16', 'home_team' => 'Argentina',               'away_team' => 'Argelia'],
            ['match_number' => 20, 'group_name' => 'J', 'match_date' => '2026-06-16', 'home_team' => 'Austria',                 'away_team' => 'Jordania'],
            ['match_number' => 21, 'group_name' => 'K', 'match_date' => '2026-06-17', 'home_team' => 'Portugal',                'away_team' => 'RD Congo'],
            ['match_number' => 22, 'group_name' => 'L', 'match_date' => '2026-06-17', 'home_team' => 'Inglaterra',              'away_team' => 'Croacia'],
            ['match_number' => 23, 'group_name' => 'L', 'match_date' => '2026-06-17', 'home_team' => 'Ghana',                   'away_team' => 'Panamá'],
            ['match_number' => 24, 'group_name' => 'K', 'match_date' => '2026-06-17', 'home_team' => 'Uzbekistán',              'away_team' => 'Colombia'],

            // Jornada 2
            ['match_number' => 25, 'group_name' => 'A', 'match_date' => '2026-06-18', 'home_team' => 'República Checa',         'away_team' => 'Sudáfrica'],
            ['match_number' => 26, 'group_name' => 'B', 'match_date' => '2026-06-18', 'home_team' => 'Suiza',                   'away_team' => 'Bosnia y Herzegovina'],
            ['match_number' => 27, 'group_name' => 'B', 'match_date' => '2026-06-18', 'home_team' => 'Canadá',                  'away_team' => 'Catar'],
            ['match_number' => 28, 'group_name' => 'A', 'match_date' => '2026-06-18', 'home_team' => 'México',                  'away_team' => 'República de Corea'],
            ['match_number' => 29, 'group_name' => 'D', 'match_date' => '2026-06-19', 'home_team' => 'Estados Unidos',          'away_team' => 'Australia'],
            ['match_number' => 30, 'group_name' => 'C', 'match_date' => '2026-06-19', 'home_team' => 'Escocia',                 'away_team' => 'Marruecos'],
            ['match_number' => 31, 'group_name' => 'C', 'match_date' => '2026-06-19', 'home_team' => 'Brasil',                  'away_team' => 'Haití'],
            ['match_number' => 32, 'group_name' => 'D', 'match_date' => '2026-06-19', 'home_team' => 'Turquía',                 'away_team' => 'Paraguay'],
            ['match_number' => 33, 'group_name' => 'F', 'match_date' => '2026-06-20', 'home_team' => 'Países Bajos',            'away_team' => 'Suecia'],
            ['match_number' => 34, 'group_name' => 'E', 'match_date' => '2026-06-20', 'home_team' => 'Alemania',                'away_team' => 'Costa de Marfil'],
            ['match_number' => 35, 'group_name' => 'E', 'match_date' => '2026-06-20', 'home_team' => 'Ecuador',                 'away_team' => 'Curazao'],
            ['match_number' => 36, 'group_name' => 'F', 'match_date' => '2026-06-20', 'home_team' => 'Túnez',                   'away_team' => 'Japón'],

            // Jornada 3
            ['match_number' => 37, 'group_name' => 'H', 'match_date' => '2026-06-21', 'home_team' => 'España',                  'away_team' => 'Arabia Saudí'],
            ['match_number' => 38, 'group_name' => 'G', 'match_date' => '2026-06-21', 'home_team' => 'Bélgica',                 'away_team' => 'Irán'],
            ['match_number' => 39, 'group_name' => 'H', 'match_date' => '2026-06-21', 'home_team' => 'Uruguay',                 'away_team' => 'Cabo Verde'],
            ['match_number' => 40, 'group_name' => 'G', 'match_date' => '2026-06-21', 'home_team' => 'Nueva Zelanda',           'away_team' => 'Egipto'],
            ['match_number' => 41, 'group_name' => 'J', 'match_date' => '2026-06-22', 'home_team' => 'Argentina',               'away_team' => 'Austria'],
            ['match_number' => 42, 'group_name' => 'I', 'match_date' => '2026-06-22', 'home_team' => 'Francia',                 'away_team' => 'Irak'],
            ['match_number' => 43, 'group_name' => 'I', 'match_date' => '2026-06-22', 'home_team' => 'Noruega',                 'away_team' => 'Senegal'],
            ['match_number' => 44, 'group_name' => 'J', 'match_date' => '2026-06-22', 'home_team' => 'Jordania',                'away_team' => 'Argelia'],
            ['match_number' => 45, 'group_name' => 'K', 'match_date' => '2026-06-23', 'home_team' => 'Portugal',                'away_team' => 'Uzbekistán'],
            ['match_number' => 46, 'group_name' => 'L', 'match_date' => '2026-06-23', 'home_team' => 'Inglaterra',              'away_team' => 'Ghana'],
            ['match_number' => 47, 'group_name' => 'L', 'match_date' => '2026-06-23', 'home_team' => 'Panamá',                  'away_team' => 'Croacia'],
            ['match_number' => 48, 'group_name' => 'K', 'match_date' => '2026-06-23', 'home_team' => 'Colombia',                'away_team' => 'RD Congo'],
            ['match_number' => 49, 'group_name' => 'B', 'match_date' => '2026-06-24', 'home_team' => 'Suiza',                   'away_team' => 'Canadá'],
            ['match_number' => 50, 'group_name' => 'B', 'match_date' => '2026-06-24', 'home_team' => 'Bosnia y Herzegovina',    'away_team' => 'Catar'],
            ['match_number' => 51, 'group_name' => 'C', 'match_date' => '2026-06-24', 'home_team' => 'Escocia',                 'away_team' => 'Brasil'],
            ['match_number' => 52, 'group_name' => 'C', 'match_date' => '2026-06-24', 'home_team' => 'Marruecos',               'away_team' => 'Haití'],
            ['match_number' => 53, 'group_name' => 'A', 'match_date' => '2026-06-24', 'home_team' => 'República Checa',         'away_team' => 'México'],
            ['match_number' => 54, 'group_name' => 'A', 'match_date' => '2026-06-24', 'home_team' => 'Sudáfrica',               'away_team' => 'República de Corea'],
            ['match_number' => 55, 'group_name' => 'E', 'match_date' => '2026-06-25', 'home_team' => 'Curazao',                 'away_team' => 'Costa de Marfil'],
            ['match_number' => 56, 'group_name' => 'E', 'match_date' => '2026-06-25', 'home_team' => 'Ecuador',                 'away_team' => 'Alemania'],
            ['match_number' => 57, 'group_name' => 'F', 'match_date' => '2026-06-25', 'home_team' => 'Japón',                   'away_team' => 'Suecia'],
            ['match_number' => 58, 'group_name' => 'F', 'match_date' => '2026-06-25', 'home_team' => 'Túnez',                   'away_team' => 'Países Bajos'],
            ['match_number' => 59, 'group_name' => 'D', 'match_date' => '2026-06-25', 'home_team' => 'Turquía',                 'away_team' => 'Estados Unidos'],
            ['match_number' => 60, 'group_name' => 'D', 'match_date' => '2026-06-25', 'home_team' => 'Paraguay',                'away_team' => 'Australia'],
            ['match_number' => 61, 'group_name' => 'I', 'match_date' => '2026-06-26', 'home_team' => 'Noruega',                 'away_team' => 'Francia'],
            ['match_number' => 62, 'group_name' => 'I', 'match_date' => '2026-06-26', 'home_team' => 'Senegal',                 'away_team' => 'Irak'],
            ['match_number' => 63, 'group_name' => 'H', 'match_date' => '2026-06-26', 'home_team' => 'Cabo Verde',              'away_team' => 'Arabia Saudí'],
            ['match_number' => 64, 'group_name' => 'H', 'match_date' => '2026-06-26', 'home_team' => 'Uruguay',                 'away_team' => 'España'],
            ['match_number' => 65, 'group_name' => 'G', 'match_date' => '2026-06-27', 'home_team' => 'Egipto',                  'away_team' => 'Irán'],
            ['match_number' => 66, 'group_name' => 'G', 'match_date' => '2026-06-27', 'home_team' => 'Nueva Zelanda',           'away_team' => 'Bélgica'],
            ['match_number' => 67, 'group_name' => 'L', 'match_date' => '2026-06-27', 'home_team' => 'Panamá',                  'away_team' => 'Inglaterra'],
            ['match_number' => 68, 'group_name' => 'L', 'match_date' => '2026-06-27', 'home_team' => 'Croacia',                 'away_team' => 'Ghana'],
            ['match_number' => 69, 'group_name' => 'K', 'match_date' => '2026-06-27', 'home_team' => 'Colombia',                'away_team' => 'Portugal'],
            ['match_number' => 70, 'group_name' => 'K', 'match_date' => '2026-06-27', 'home_team' => 'RD Congo',                'away_team' => 'Uzbekistán'],
            ['match_number' => 71, 'group_name' => 'J', 'match_date' => '2026-06-27', 'home_team' => 'Argelia',                 'away_team' => 'Austria'],
            ['match_number' => 72, 'group_name' => 'J', 'match_date' => '2026-06-27', 'home_team' => 'Jordania',                'away_team' => 'Argentina'],
        ];

        foreach ($fixtures as $fixture) {
            Fixture::updateOrCreate(
                ['match_number' => $fixture['match_number']],
                $fixture,
            );
        }
    }
}
