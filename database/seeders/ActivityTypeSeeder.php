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
                "description" => "Active throughout the event's duration - the players with the best scores and fastest times by the end are rewarded with the most tickets.",
            ],
            [
                "name" => "Completion Challenges - Solo",
                "icon" => "mdi-ticket",
                "description" => "Complete the given goal and be rewarded with tickets. These can be done at any point throughout the event.",
            ],
            [
                "name" => "Completion Challenges - Team",
                "icon" => "mdi-ticket",
                "description" => "Complete the given goal and be rewarded with tickets. These can be done at any point throughout the event.",
                "hidden" => true,
            ],
            [
                "name" => "Matchmaking Wins",
                "icon" => "mdi-ticket",
                "description" => "Win games and get tickets, but with much higher limits per player.",
            ],
            [
                "name" => "Races",
                "icon" => "mdi-flag-checkered",
                "description" => "Receive tickets based on your final placement in the race. Note: Some races will be mystery races and will be revealed shortly before they begin!",
            ],
            [
                "name" => "Competitions",
                "icon" => "mdi-tournament",
                "description" => "Receive tickets based on your final placement and/or performance! The means of rewarding tickets will heavily vary based on the competition\'s format. Note: Players must check in to tournaments on Challonge in the hour leading up to the starting time or will be removed!"
            ],
            [
                "name" => "Contests",
                "icon" => "mdi-brush",
                "description" => "Do what the task asks and a panel of judges will select the best of the bunch and reward tickets accordingly at a scheduled time. These can be worked on at any point prior to the scheduled time of judgment.",
            ]
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}
