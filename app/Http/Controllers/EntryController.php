<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        if ($activity->revealed_at && $activity->revealed_at > now()) {
            abort(404);
        }

        $validated = $request->validate([
            "proof" => "required",
            "result" => "required|numeric",
        ]);

        $user = Auth::user();

        $user->entries()->where("activity_id", $activity->id)->delete();

        Entry::create([
            "user_id" => $user->id,
            "activity_id" => $activity->id,
            "proof" => $validated["proof"],
            "result" => $validated["result"],
        ]);

        $activity->updateLeaderboard();

        session()->flash('flash', [
            'type' => 'success',
            'text' => 'Submission successful!',
            'bla' => rand(1, 100),
        ]);

        return redirect()->back();
    }
}
