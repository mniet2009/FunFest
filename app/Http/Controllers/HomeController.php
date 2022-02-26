<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        $activityTypes = ActivityType::where("hidden", false)
            ->select("description", "icon", "name")
            ->get();
        return Inertia::render('home', compact('activityTypes'))
            ->withViewData([
                "title" => "Mystery Fun Fest",
                "description" => "Mystery Fun Fest is a cooperative event in which two teams compete to accrue the most tickets throughout its 17-day duration.",
                "image" => asset("/img/home.jpg"),
            ]);
    }
}
