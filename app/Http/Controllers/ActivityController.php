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
        $activities = Activity::all();

        return Inertia::render('activities/index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        return Inertia::render('activities/show', compact('activity'));
    }

    public function complete(Activity $activity)
    {
        $user = Auth::user();

        $user->activities()->attach($activity);

        return redirect()->back();
    }
}
