<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Activity extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function completions()
    {
        return $this->hasMany(Completion::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, "completions")->withTimestamps();
    }

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function updateLeaderboard()
    {
        $this->users()->detach();

        if ($this->leaderboard_type_id == 1) { // score (descending)
            $entries = $this->entries()->orderBy("result", "desc")->get();
        } else { // time (ascending)
            $entries = $this->entries()->orderBy("result", "asc")->get();
        }

        $previousResult = null;
        $previousTickets = null;
        $previousPlacement = null;

        foreach ($entries as $i => $entry) {
            $placement = $i + 1;
            $tickets = 0;

            if ($placement < count($this->leaderboard_tickets)) {
                $tickets = $this->leaderboard_tickets[$placement - 1];
            }

            if ($entry->result == $previousResult) {
                $tickets = $previousTickets;
                $placement = $previousPlacement;
            }

            $previousResult = $entry->result;
            $previousTickets = $tickets;
            $previousPlacement = $placement;

            $entry->user->activities()->attach($this->id, [
                'proof' => $entry->proof,
                'result' => $entry->result,
                'placement' => $placement,
                "tickets" => $tickets,
            ]);
        }
    }

    public function visible()
    {
        return is_null($this->revealed_at) || $this->revealed_at <= now();
    }

    public static function forUser($user)
    {
        return Activity::whereNull("parent_id")
            ->where(function ($query) {
                $query->where("revealed_at", "<=", now())
                    ->orWhereNull("revealed_at");
            })
            ->with("activityType")
            ->with(["completions" => function ($query) use ($user) {
                $query->groupBy("activity_id", "user_id", "placement")
                    ->select("user_id", "activity_id", "placement", DB::raw("SUM(tickets) as tickets"))
                    ->where("user_id", $user->id);
            }])
            ->with("children")
            ->with(["children.completions" => function ($query) use ($user) {
                $query->groupBy("activity_id", "user_id", "placement")
                    ->select("user_id", "activity_id", "placement", DB::raw("SUM(tickets) as tickets"))
                    ->where("user_id", $user->id);
            }])
            ->orderBy("name", "asc");
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "revealed_at" => "datetime",
        "event_at" => "datetime",
        'leaderboard_tickets' => 'array',
    ];
}
