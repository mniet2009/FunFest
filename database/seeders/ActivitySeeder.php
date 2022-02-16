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
                Activity::factory(rand(1, 3))->create([
                    "name" => $activityName . " child",
                    "image" => $url,
                    "slug" => str_replace(" ", "-", $activityName),
                    "parent_id" => $activity->id,
                ]);
            }
        }
    }
}
