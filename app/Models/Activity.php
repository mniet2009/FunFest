<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'slug';
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
