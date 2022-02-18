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
            [
                "name" => "Score / Time Attacks",
                "icon" => "mdi-podium",
            ],
            [
                "name" => "Completion Challenges - Solo",
                "icon" => "mdi-ticket",
            ],
            [
                "name" => "Completion Challenges - Team",
                "icon" => "mdi-ticket",
            ],
            [
                "name" => "Matchmaking Wins",
                "icon" => "mdi-ticket",
            ],
            [
                "name" => "Races",
                "icon" => "mdi-flag-checkered",
            ],
            [
                "name" => "Competitions",
                "icon" => "mdi-tournament",
            ],
            [
                "name" => "Contests",
                "icon" => "mdi-brush",
            ]
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}
