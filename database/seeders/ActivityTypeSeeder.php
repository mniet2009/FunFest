<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            "Score / Time Attacks",
            "Completion Challenges - Solo",
            "Completion Challenges - Team",
            "Matchmaking Wins",
            "Races",
            "Competitions",
        ];

        foreach ($types as $type) {
            ActivityType::create([
                'name' => $type,
            ]);
        }
    }
}
