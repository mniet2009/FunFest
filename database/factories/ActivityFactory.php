<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($this->faker));

        $name = $this->faker->words(2, true);

        $activityTypeId = ActivityType::all()->random()->id;

        // completion stuff can have limits
        $limit = 1;

        if (in_array($activityTypeId, [4])) {
            $limit = rand(2, 5);
        }


        // score/time attacks need a leaderboard type
        $leaderboardTypeId = null;
        $leaderboardTickets = null;

        if ($activityTypeId == 1) {
            $leaderboardTypeId = rand(1, 2);
        }

        if (in_array($activityTypeId, [1, 5, 6, 7])) {
            $leaderboardTickets = [20, 15, 10, 8, 6, 5, 4, 3, 2, 1];
        }

        $event_at = null;

        // events need an event date
        if (in_array($activityTypeId, [5, 6])) {
            $event_at = $this->faker->dateTimeBetween("+1 week", "+1 month");
        }

        return [
            "tickets" => $this->faker->numberBetween(1, 100),
            "name" => $name,
            "slug" => str_replace(" ", "-", $name),
            "excerpt" => $this->faker->sentence,
            "description" => $this->faker->markdown(),
            "image" => "/storage/activities/1.jpg",
            "limit" => $limit,
            "activity_type_id" => $activityTypeId,
            "leaderboard_type_id" => $leaderboardTypeId,
            "leaderboard_tickets" => $leaderboardTickets,
            "event_at" => $event_at,
        ];
    }
}
