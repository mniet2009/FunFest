<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $files = Storage::allFiles("public/activities");

        foreach ($files as $file) {
            $activityName = basename($file, ".png");
            $url = "/storage/activities/{$activityName}.png";

            $activity = Activity::factory()->create([
                "name" => $activityName,
                "image" => $url,
                "slug" => str_replace(" ", "-", $activityName),
            ]);

            if (in_array($activity->activity_type_id, [2, 3]) && rand(0, 1)) {
                for ($i = 0; $i < rand(1, 8); $i++) {
                    $name = $activityName . " child " . $i;
                    Activity::factory()->create([
                        "name" => $name,
                        "image" => $url,
                        "slug" => str_replace(" ", "-", $name),
                        "parent_id" => $activity->id,
                        "activity_type_id" => rand(3, 4),
                        "leaderboard_tickets" => null,
                        "limit" => 1,
                    ]);
                }
            }
        }
    }
}
