<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        $teams = Team::withSum("completions", "tickets")
            ->with(["users" => function ($query) {
                $query
                    ->select("id", "team_id", "username", "slug")
                    ->withSum("completions", "tickets")
                    ->orderBy("completions_sum_tickets", "desc")
                    ->orderBy("username", "asc");
            }])
            ->get();

        foreach ($teams as $team) {
            foreach ($team->users as $user) {
                if ($user->username == "Alpha-5") {
                    $user->completions_sum_tickets -= 1000;
                }
            }
        }

        return Inertia::render('Team/Index', compact("teams"))
            ->withViewData([
                "title" => "Standings",
                "description" => "View the Fun Fest standings",
                "image" => asset("img/standings.jpg"),
            ]);
    }
}
