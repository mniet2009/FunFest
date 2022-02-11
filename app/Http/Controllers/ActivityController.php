<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(["completions" => function ($query) {
            $query->where("user_id", Auth::id());
        }])->get();

        return Inertia::render('Activity/Index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $activity->load(["completions" => function ($query) {
            $query->where("user_id", Auth::id());
        }])->get();

        return Inertia::render('Activity/Show', compact('activity'));
    }

    public function complete(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            "proof" => "required"
        ]);

        $user = Auth::user();

        $user->activities()->attach($activity, ["proof" => $validated["proof"]]);

        return redirect()->back();
    }
}
