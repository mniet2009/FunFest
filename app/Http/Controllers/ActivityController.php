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
        $activities = Activity::whereNull("parent_id")
            ->where(function ($query) {
                $query->where("revealed_at", "<=", now())
                    ->orWhereNull("revealed_at");
            })
            ->with("activityType")
            ->with(["completions" => function ($query) {
                $query->groupBy("activity_id", "user_id", "placement")
                    ->select("user_id", "activity_id", "placement", DB::raw("SUM(tickets) as tickets"))
                    ->where("user_id", Auth::id());
            }])
            ->with("children")
            ->with(["children.completions" => function ($query) {
                $query->groupBy("activity_id", "user_id", "placement")
                    ->select("user_id", "activity_id", "placement", DB::raw("SUM(tickets) as tickets"))
                    ->where("user_id", Auth::id());
            }])
            ->get();

        // dd($activities->toArray());

        return Inertia::render('Activity/Index', compact('activities', 'activityTypes'));
    }

    public function show(Activity $activity)
    {
        if ($activity->revealed_at && $activity->revealed_at > now()) {
            abort(404);
        }
        $activity->load(["completions" => function ($query) {
            $query->groupBy("activity_id", "user_id", "result", "placement", "tickets")
                ->select("user_id", "activity_id", "result", "placement", "tickets", DB::raw("COUNT(*) as count"))
                ->with("user")
                ->orderBy("count", "desc")
                ->orderBy(DB::raw("MIN(id)"), "asc");
        }]);

        $activity->load("children")
            ->load(["children.completions" => function ($query) {
                $query->groupBy("activity_id", "user_id", "result", "placement", "tickets")
                    ->select("user_id", "activity_id", "result", "placement", "tickets", DB::raw("COUNT(*) as count"))
                    ->with("user")
                    ->orderBy("count", "desc")
                    ->orderBy(DB::raw("MIN(id)"), "asc");
            }]);

        return Inertia::render('Activity/Show', compact('activity'));
    }

    public function complete(Request $request, Activity $activity)
    {
        if ($activity->revealed_at && $activity->revealed_at > now()) {
            abort(404);
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
            'bla' => rand(1, 100),
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
