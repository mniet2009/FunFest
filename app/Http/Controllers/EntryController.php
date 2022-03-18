<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Entry;
use App\Models\EntryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        // gotta be logged in
        if (!Auth::check()) {
            abort(401);
        }

        // activity needs to be visible (ie not before reveal date)
        if (!$activity->visible()) {
            abort(404);
        }

        // only score/time attacks can have entries
        if ($activity->activity_type_id != 1) {
            abort(400);
        }

        $validated = $request->validate([
            "proof" => "required",
            "result" => "required|numeric",
        ]);

        $user = Auth::user();

        $activity->entries()
            ->where("user_id", $user->id)
            ->delete();

        Entry::create([
            "user_id" => $user->id,
            "activity_id" => $activity->id,
            "proof" => $validated["proof"],
            "result" => $validated["result"],
        ]);

        EntryHistory::create([
            "user_id" => $user->id,
            "activity_id" => $activity->id,
            "proof" => $validated["proof"],
            "result" => $validated["result"],
        ]);

        $activity->updateLeaderboard();

        $completion = $activity->completions()->where("user_id", $user->id)->first();

        session()->flash('flash', [
            'type' => 'success',
            'text' => "Submission successful! You're in " . ordinal($completion->placement) . " place!",
        ]);

        return redirect()->back();
    }
}
