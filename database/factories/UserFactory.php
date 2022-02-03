<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'username' => $this->faker->userName,
            'discriminator' => $this->faker->randomNumber(4),
            'email' => $this->faker->email,
            'avatar' => $this->faker->imageUrl(),
            'verified' => $this->faker->boolean,
            'locale' => $this->faker->locale,
            'mfa_enabled' => $this->faker->boolean,
            'refresh_token' => $this->faker->uuid,
        ];
    }
}
