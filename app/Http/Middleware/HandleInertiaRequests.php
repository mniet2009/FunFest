<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $auth = [];
        $can = [];

        if (Auth::check()) {
            $user = Auth::user();

            $auth["user"] = [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'signedUp' => $user->signed_up,
                'team_id' => $user->team_id,
                'color' => $user->team ? $user->team->color : null,
            ];

            $can["assign points"] = $user->can("assign points");
        }

        $flash = $request->session()->get('flash');


        return array_merge(parent::share($request), [
            'signupsOpen' => config("funfest.signups_open"),
            'started' => config("funfest.started"),
            'auth' => $auth,
            'flash' => $flash,
            'can' => $can
        ]);
    }
}
