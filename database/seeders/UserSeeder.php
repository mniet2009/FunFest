<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Completion;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use \GuzzleHttp;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->delete();
        Entry::all()->delete();
        Completion::all()->delete();

        $maurice = User::factory()->create([
            "id" => "84300263346704384",
            "username" => "Maurice",
            "avatar" => "https://cdn.discordapp.com/avatars/84300263346704384/e932a3333e0424d2d7594427179e13e9"
        ]);

        $bony = User::factory()->create([
            "id" => "167157504847904768",
            "username" => "bony",
            "avatar" => "https://cdn.discordapp.com/avatars/167157504847904768/0bb2e819d55feb4e69a993e1362d118b"
        ]);

        $maurice->assignRole("Admin");

        $client = new GuzzleHttp\Client();

        User::factory()
            ->count(50)
            ->create()
            ->each(function (User $user) use ($client) {
                $avatarUrl = "https://picsum.photos/100?{$user->id}";

                // gotta cache avatars
                $path = Storage::path('public/avatars/' . $user->id);
                $client->request('GET', $avatarUrl, [
                    'sink' => $path,
                ]);
            });

        User::all()->each(function ($user) {
            Activity::all()->each(function ($activity) use ($user) {
                if ($activity->children->count() == 0 && rand(0, 1) && (is_null($activity->event_at) || $activity->event_at->isPast())) {
                    if (in_array($activity->activity_type_id, [1])) {
                        // completions are calculated from entries for these
                        Entry::create([
                            "activity_id" => $activity->id,
                            "user_id" => $user->id,
                            "result" => rand(1, 500000),
                            "proof" => "https://i.ytimg.com/vi/D0q0QeQbw9U/maxresdefault.jpg"
                        ]);
                    } else {
                        $placement = null;

                        if (in_array($activity->activity_type_id, [5, 6, 7])) {
                            // placements still exist in races and competitions though
                            $placement = rand(1, 16);
                        }

                        for ($i = 0; $i < rand(1, $activity->limit); $i++) {
                            $user->activities()->attach($activity, [
                                "tickets" => $activity->tickets,
                                "placement" => $placement,
                                "proof" => "https://i.ytimg.com/vi/D0q0QeQbw9U/maxresdefault.jpg"
                            ]);
                        }
                    }
                }
            });
        });

        // calculate leaderboard completions based on entries
        Activity::all()->each(function ($activity) {
            if (in_array($activity->activity_type_id, [1])) {
                $activity->updateLeaderboard();
            }
        });
    }
}
