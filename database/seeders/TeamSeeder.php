<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::create([
            "name" => "The Angy Cows",
            "color" => "#2196F3",
        ]);

        Team::create([
            "name" => "Team Do It",
            "color" => "#F44336",
        ]);

        // Team::create([
        //     "name" => "Baba is Team",
        //     "color" => "#FFC107",
        // ]);
    }
}
