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

        Activity::all()->each(function ($activity) use ($maurice) {
            if (rand(0, 1)) {
                $maurice->activities()->attach($activity);
            }
        });

        User::factory()
            ->count(50)
            ->create();
    }
}
