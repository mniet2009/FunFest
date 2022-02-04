<?php

namespace Database\Factories;

use App\Models\Completion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompletionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Completion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "proof" => "https://i.ytimg.com/vi/D0q0QeQbw9U/maxresdefault.jpg"
        ];
    }
}
