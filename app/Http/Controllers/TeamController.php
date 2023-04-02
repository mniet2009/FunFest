<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withSum("completions", "tickets")
            ->with(["users" => function ($query) {
                $query
                    ->select("id", "team_id", "username", "slug")
                    ->withSum("completions", "tickets")
                    ->orderBy("completions_sum_tickets", "desc")
                    ->orderBy("username", "asc");
            }])
            ->with(["completions" => function ($query) {
                $query->select("activity_id", DB::raw("SUM(tickets) as tickets"))
                    ->groupBy("laravel_through_key", "activity_id")
                    ->with("activity:id,name,parent_id")
                    ->orderBy("activity_id");
            }])
            ->get();

        $activities = Activity::select("id", "name")->whereNull("parent_id")->get();

        $teamActivityTickets = [];

        foreach ($activities as $activity) {
            $teamActivityTickets[$activity->id] = [
                "name" => $activity->name,
                "tickets" => [0, 0],
            ];
        }

        // add all team activities to the parent activity
        $teams->each(function ($team) use ($activities, &$teamActivityTickets) {
            $team->completions->each(function ($completion) use ($team, $activities, &$teamActivityTickets) {
                $targetId = $completion->activity->parent_id ?? $completion->activity->id;

                $activity = $activities->firstWhere("id", $targetId);

                if ($activity) {
                    $teamActivityTickets[$activity->id]["tickets"][$team->id - 1] += $completion->tickets;
                }
            });
        });

        $teamActivityTickets = array_values($teamActivityTickets);

        foreach ($teams as $team) {
            foreach ($team->users as $user) {
                if ($user->username == "Alpha-5") {
                    $user->completions_sum_tickets -= 1000;
                }
            }
        }

        return Inertia::render('Team/Index', compact("teams", "teamActivityTickets"))
            ->withViewData([
                "title" => "Standings",
                "description" => "View the Fun Fest standings",
                "image" => asset("img/standings.jpg"),
            ]);
    }
}
