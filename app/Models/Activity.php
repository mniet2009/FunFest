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
