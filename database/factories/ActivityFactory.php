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
        $name = $this->faker->words(2, true);

        return [
            "tickets" => $this->faker->numberBetween(1, 100),
            "name" => $name,
            "slug" => str_replace(" ", "-", $name),
            "description" => $this->faker->sentence,
            "image" => "/storage/activities/1.jpg",
            "limit" => null,
            "activity_type_id" => ActivityType::all()->random()->id,
        ];
    }
}
