<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maurice = User::factory()->create([
            "id" => "84300263346704384",
            "username" => "Maurice",
            "avatar" => "https://cdn.discordapp.com/avatars/84300263346704384/e932a3333e0424d2d7594427179e13e9"
        ]);

        $maurice->assignRole("Admin");

        User::factory()
            ->count(50)
            ->create();

        User::all()->each(function ($user) {
            Activity::all()->each(function ($activity) use ($user) {
                if (rand(0, 1)) {
                    $user->activities()->attach($activity, [
                        "tickets" => $activity->tickets,
                        "proof" => "https://i.ytimg.com/vi/D0q0QeQbw9U/maxresdefault.jpg",
                    ]);
                }
            });
        });
    }
}
