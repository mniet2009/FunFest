<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activityTypes = ActivityType::select("name", "id", "icon")->get();
        $activities = Activity::forUser(Auth::user())->get();
        $activities = Activity::filterUnrevealed($activities);

        return Inertia::render('Activity/Index', compact('activities', 'activityTypes'))
            ->withViewData([
                "title" => "Activities",
                "description" => "View all Fun Fest activities",
                "image" => asset("/img/activities.jpg"),
            ]);
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
        $activityArray = $activity->only(["id", "slug", "activity_type_id", "name", "description", "children", "image", "completions", "tickets", "limit", "leaderboard_type_id", "leaderboard_tickets", "event_at"]);

        $entries = null;

        if ($activity->activity_type_id == 1) {
            // We need leaderboard entry data
            $entries = $activity->users()->with("entries", function ($query) use ($activity) {
                $query->where("activity_id", $activity->id)
                    ->select("user_id", "result", "created_at")
                    ->orderBy("created_at", "asc")
                    ->withTrashed();
            })
                ->select("users.id", "username", "team_id")
                ->get();
        }

        return Inertia::render('Activity/Show', [
            'activity' => $activityArray,
            'teams' => $teams,
            "entries" => $entries,
        ])
            ->withViewData([
                "title" => $activity->name,
                "description" => $activity->excerpt,
                "image" => asset($activity->image),
            ]);
    }

    public function complete(Request $request, Activity $activity)
    {
        // gotta be logged in
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();

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

        // if it's a team activity (id 4), the user is replaced by the team user (user_id = team_id)
        if ($activity->activity_type_id == 4) {
            // but only if the user can redeem for the team!
            if (!$user->can("redeem team points")) {
                abort(403);
            }

            $user = User::find($user->team_id);
        }

        // count how often the user has completed the activity yet
        $completions = $activity->completions()->where("user_id", $user->id)->count();
        // don't allow more than the limit
        if ($completions >= $activity->limit) {
            abort(400);
        }

        $validated = $request->validate([
            "proof" => "required"
        ]);


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

        return Inertia::render('Activity/Schedule', compact('activities'))
            ->withViewData([
                "title" => "Schedule",
                "description" => "All upcoming Fun Fest activities",
                "image" => asset("img/schedule.jpg"),
            ]);
    }

    public function pointsForm(Activity $activity)
    {
        $users = User::whereNotNull("team_id")->orderBy("username", "asc")->get();

        return Inertia::render('Activity/PointsForm', compact('activity', 'users'));
    }

    public function assignPoints(Activity $activity)
    {
        $validated = request()->validate([
            "users" => "required|array",
        ]);

        // delete all completions
        $activity->completions()->delete();

        // assign points to users
        foreach ($validated["users"] as $i => $userId) {
            // get ticket count for placement

            $tickets = 2; // 2 tickets for participation

            if ($i < count($activity->leaderboard_tickets)) {
                $tickets = $activity->leaderboard_tickets[$i];
            }

            $user = User::find($userId);
            $user->activities()->attach($activity, [
                "tickets" => $tickets,
                "placement" => $i + 1,
            ]);
        }

        return redirect(route("activities.show", $activity));
    }
}
