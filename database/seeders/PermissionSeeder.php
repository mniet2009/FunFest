<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if admin role exists
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(["name" => "Admin"]);

            $assignPointsPermission = Permission::create(['name' => 'assign points']);
            $adminRole->givePermissionTo($assignPointsPermission);
        }


        // check if team captain role exists
        $teamCaptainRole = Role::where('name', 'Team Captain')->first();
        if (!$teamCaptainRole) {
            $teamCaptainRole = Role::create(["name" => "Team Captain"]);

            $redeemTeamPointsPermission = Permission::create(['name' => 'redeem team points']);
            $teamCaptainRole->givePermissionTo($redeemTeamPointsPermission);
        }
    }
}
