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

        $entries = $this->entries()->orderBy("result", "desc")->get();

        foreach ($entries as $placement => $entry) {
            $entry->user->activities()->attach($this->id, [
                'proof' => $entry->proof,
                'result' => $entry->result,
                'placement' => $placement + 1,
                "tickets" => 1,
            ]);
        }
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "revealed_at" => "datetime",
        "event_at" => "datetime",
    ];
}
