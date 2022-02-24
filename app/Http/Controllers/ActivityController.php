<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activityTypes = ActivityType::all();
        $activities = Activity::forUser(Auth::user())->get();

        return Inertia::render('Activity/Index', compact('activities', 'activityTypes'));
    }

    public function show(Activity $activity)
    {

        if (!$activity->visible()) {
            abort(404);
        }

        $activity->load(["completions" => function ($query) {
            $query->groupBy("activity_id", "user_id", "result", "placement")
                ->select("user_id", "activity_id", "result", "placement", DB::raw("SUM(tickets) as tickets"), DB::raw("COUNT(*) as count"))
                ->with("user")
                ->orderBy("count", "desc")
                ->orderBy("placement", "asc")
                ->orderBy(DB::raw("MIN(id)"), "asc");
        }]);

        $activity->load("children")
            ->load(["children.completions" => function ($query) {
                $query->groupBy("activity_id", "user_id", "result", "placement")
                    ->select("user_id", "activity_id", "result", "placement", DB::raw("SUM(tickets) as tickets"), DB::raw("COUNT(*) as count"))
                    ->with("user")
                    ->orderBy("count", "desc")
                    ->orderBy("placement", "asc")
                    ->orderBy(DB::raw("MIN(id)"), "asc");
            }]);

        return Inertia::render('Activity/Show', compact('activity'));
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
            ->orderBy("event_at")
            ->get();

        return Inertia::render('Activity/Schedule', compact('activities'));
    }
}
