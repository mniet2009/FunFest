<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateTeamUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'funfest:generate-team-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a user for both teams, for team event points.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Team::all()->each(function ($team) {
            $user = User::find($team->id);

            if (!$user) {
                User::create([
                    "id" => $team->id,
                    "username" => $team->name,
                    "discriminator" => "0000",
                    "email" => "no@example.com",
                    "avatar" => $team->id,
                    "verified" => true,
                    "locale" => "en-US",
                    "mfa_enabled" => false,
                    "refresh_token" => "no",
                    "remember_token" => "no",
                    "friends" => "",
                    "likelihood" => 1,
                    "signed_up" => true,
                    "slug" => Str::slug($team->name),
                    "team_id" => $team->id,
                ]);
            }
        });

        return 0;
    }
}
