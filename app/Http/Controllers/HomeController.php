<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        $activityTypes = ActivityType::all();
        return Inertia::render('home', compact('activityTypes'));
    }

    public function about()
    {
        return Inertia::render('about');
    }
}
