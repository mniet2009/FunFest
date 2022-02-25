<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activityTypes = ActivityType::select("name", "id")->get();
        $activities = Activity::forUser(Auth::user())->get();

        return Inertia::render('Activity/Index', compact('activities', 'activityTypes'));
    }

    public function show(Activity $activity)
    {
        $teams = Team::select("id", "color")->get();
        if (!$activity->visible()) {
            abort(404); // https://c.tenor.com/iaqJajlqXq4AAAAM/peace-out-disappear.gif
        }

        function groupCompletions($query)
        {
            $query->groupBy("activity_id", "user_id", "result", "placement")
                ->select("user_id", "activity_id", "result", "placement", DB::raw("SUM(tickets) as tickets"), DB::raw("COUNT(*) as count"))
                ->with("user:id,username,avatar,slug,team_id")
                ->orderBy("count", "desc")
                ->orderBy("placement", "asc")
                ->orderBy(DB::raw("MIN(id)"), "asc");
        }

        $activity->load(["completions" => function ($query) {
            groupCompletions($query);
        }]);

        $activity->load([
            "children:id,slug,parent_id,activity_type_id,name,excerpt,tickets,limit",
            "children.completions" => function ($query) {
                groupCompletions($query);
            }
        ]);

        // filter activity
        $activity = $activity->only(["id", "slug", "activity_type_id", "name", "description", "children", "image", "completions", "tickets", "limit", "leaderboard_type_id", "leaderboard_tickets", "event_at"]);

        return Inertia::render('Activity/Show', compact('activity', 'teams'));
    }

    public function complete(Request $request, Activity $activity)
    {
        // gotta be logged in
        if (!Auth::check()) {
            abort(401);
        }

        // activity needs to be visible (ie not before reveal date)
        if (!$activity->visible()) {
            abort(404);
        }

        // only activities of types 2, 3, 4 can be redeemed
        if (!in_array($activity->activity_type_id, [2, 3, 4])) {
            abort(400);
        }

        // activites with children can not be redeemed
        if ($activity->children->count() > 0) {
            abort(400);
        }


        $validated = $request->validate([
            "proof" => "required"
        ]);

        $user = Auth::user();

        $user->activities()->attach($activity, [
            "proof" => $validated["proof"],
            "tickets" => $activity->tickets,
        ]);

        session()->flash('flash', [
            'type' => 'success',
            'text' => "{$activity->tickets} Tickets redeemed!",
        ]);

        return redirect()->back();
    }

    public function schedule()
    {
        $activities = Activity::whereNull("parent_id")
            ->where(function ($query) {
                $query->where("revealed_at", "<=", now())
                    ->orWhereNull("revealed_at");
            })
            ->whereNotNull("event_at")
            ->where("event_at", ">", now())
            ->orderBy("event_at", "ASC")
            ->select("name", "slug", "event_at", "image")
            ->get();

        return Inertia::render('Activity/Schedule', compact('activities'));
    }
}
