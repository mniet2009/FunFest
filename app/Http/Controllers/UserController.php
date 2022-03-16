<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function signUpForm()
    {
        $user = Auth::user();

        $initialLikelihood = $user->likelihood ?? null;
        $initialFriends = $user->friends ?? "";

        return Inertia::render('User/SignUp', compact("initialLikelihood", "initialFriends"))
            ->withViewData([
                "title" => "Sign Up",
                "description" => "Sign up for all the Fun and the Fest",
                "image" => asset("img/signup.jpg"),
            ]);
    }

    public function signUp(Request $request)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $validated = $request->validate([
            "likelihood" => "required|numeric|min:1|max:3",
            "friends" => "",
        ]);

        $user = Auth::user();

        if ($user->signed_up) {
            session()->flash('flash', [
                'type' => 'success',
                'text' => "Signup edited!",
            ]);
        } else {
            session()->flash('flash', [
                'type' => 'success',
                'text' => "Successfully signed up!",
            ]);
        }

        $user->likelihood = $validated["likelihood"];
        $user->friends = $validated["friends"];
        $user->signed_up = true;
        $user->save();


        return redirect()->route("home");
    }

    public function show(User $user)
    {
        $activityTypes = ActivityType::all();
        $activities = Activity::forUser($user)->get();

        return Inertia::render('Activity/Index', compact('activities', 'activityTypes', "user"))
            ->withViewData([
                "title" => "{$user->username}'s Activities",
                "description" => "View {$user->username}'s progress in the Mystery Fun Fest",
                "image" => asset("img/activities.jpg"),
            ]);
    }

    public function oneTimeSet()
    {
        $list = "jakazam-9682	1
whytepanther-2703	1
dragus-0518	2
monster-dog-7524	1
phantommat-0105	1
thespinbeast-0001	2
alexi-3826	2
aspeon-5547	2
jorf-4702	2
maxale540-8693	1
teapartycthulu-2561	2
yorkie-8008	1
thegons-9675	1
t3tsuya-7408	2
lon-7438	2
aarondobbe-3832	2
jace-knight-6292	2
knighty-8036	2
keirenh-2928	1
tecspark-0477	1
polari-1162	2
juegariel-7418	2
mrmoggy-2494	2
trisscorp-1625	1
walrus-0802	1
freeball1-7976	1
arcbliss-9561	2
bladetwinswords-4028	1
bangerra-5257	1
poptart-cat-5742	2
julloninja-0158	1
jal-5639	2
metro85-6830	1
palidian-0880	2
anees-2003	2
mithical9-3736	2
mundungu-0158	2
spaceotter-9853	1
alpha-5-3174	2
komshovskixd-0976	1
someone325-6844	2
okamiofgames-8030	2
gikkman-0724	1
bony-0276	2
capndrake-9991	2
joe-5566	1
pwndnoob-9730	1
ajarmar-2906	1
bun-1087	2
5ully-6852	2
gfm-9506	1
psymar-2210	1
misterzimbu-9397	1
internetdirigible-5826	1
thorofkenya-9881	1
ch0senlast-8991	1
rhelys-3941	2
darkbladelink-1861	2
rikri-4009	2
kaiserlucas-0685	1
tapioca-0977	2
simon-0161	1
boop-bloop-4506	2
soul-devour-2002	1
some-girl-6531	1
marth-4641	1
pikapals-1312	2
pieper-1804	2
hazelshade12-5922	1
something915-7058	1
reyom-7553	1
tasty-1641	1
sanevin-4423	1
brightshadow-3975	2
dylst3r-0145	1
tornadodancer-8657	2
callback-9246	2
thatoneguy-9876	2
selfsagax-0069	1
neetsel-2882	2
oshxmir-1211	2
b4mv-7055	2
beokirby-1122	2
spock-3235	2
ffao-0784	1
timewanderer-6641	1
sg4e-7435	2
mooware-2889	1
chickenbut12-7155	1
thewhitley-2038	2
sn0wd0zer-2023	2
riocoys-7294	2
szechuansteve-6557	1
standardone-4327	1
jmepensebon-1570	2
vitoral-1256	1
constellationofdreams-6693	2
yelenazal-7565	1
lyle-9180	2
spritzt-6637	2
quaxiazu-5995	2
oneup-7856	2
binglesnort-0126	2
schrammbles-6574	1
gravestone-craving-5662	1
falseigabod-9230	1
no-egrets-4397	2
hopscotch007-6904	1
and4h-7892	2
takeru-5183	2
shoehorse-5190	2
exuno-5912	1
blasphemousroar-9136	1
lostforwords-7487	1
doicm-6219	1
iq-8868	2
moose-2267	2
withhelde-2085	1
litenang-0819	1
krankdud-1273	2
psymarth-1337	1
yugge-9712	2
maurice-5018	1
synii-6592	2
goost-7309	1
furretturret-9827	1
zenicreverie-1638	1
hell-raven-9586	1
jerryeris-6034	1
arrow-fodder-0897	2
jir-5806	2
k-honkplis-1869	1
nikojazz-7956	1";

        // loop through all lines
        foreach (explode("\n", $list) as $line) {
            // split by tab
            $data = explode("\t", $line);

            // get the user
            $user = User::where('slug', $data[0])->first();
            $user->team_id = $data[1];
            $user->save();
        }
    }
}
