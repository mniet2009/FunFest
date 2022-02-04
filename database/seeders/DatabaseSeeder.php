<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(ActivityTypeSeeder::class);
        $this->call(TeamSeeder::class);

        if (App::environment() == "local") {
            $this->call(ActivitySeeder::class);
            $this->call(UserSeeder::class);
        }
    }
}
