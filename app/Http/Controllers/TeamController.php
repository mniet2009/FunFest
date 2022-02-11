<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withSum("completions", "tickets")
            ->with(["completions" => function ($query) {
                $query->groupBy("user_id")
                    ->select("user_id", DB::raw("SUM(tickets) as tickets"))
                    ->orderBy("tickets", "desc")
                    ->with("user:id,username");
            }])
            ->get();

        return Inertia::render('Team/Index', compact("teams"));
    }
}
