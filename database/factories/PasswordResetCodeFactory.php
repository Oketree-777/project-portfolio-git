<?php

namespace Database\Factories;

use App\Models\PasswordResetCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordResetCode>
 */
class PasswordResetCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PasswordResetCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'code' => str_pad($this->faker->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'used' => false,
            'used_at' => null,
        ];
    }

    /**
     * Indicate that the code is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    /**
     * Indicate that the code is used.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'used' => true,
            'used_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
        ]);
    }

    /**
     * Indicate that the code expires soon.
     */
    public function expiresSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => $this->faker->dateTimeBetween('now', '+5 minutes'),
        ]);
    }
}
