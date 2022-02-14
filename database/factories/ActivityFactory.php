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
        $limit = rand(1, 5);

        return [
            "tickets" => $this->faker->numberBetween(1, 100),
            "name" => $name,
            "slug" => str_replace(" ", "-", $name),
            "excerpt" => $this->faker->sentence,
            "description" => $this->faker->markdown(),
            "image" => "/storage/activities/1.jpg",
            "limit" => $limit,
            "activity_type_id" => ActivityType::all()->random()->id,
        ];
    }
}
