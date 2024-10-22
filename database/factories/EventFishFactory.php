<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventFish>
 */
class EventFishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'no_ikan' => fake()->numerify('########'),
            'variety' => 'koi',
            'breeder' => fake()->randomElement(['Parent fish', 'Normal Fish']),
            'bloodline' => '-',
            'sex' => fake()->randomElement(['Male', 'Female']),
            'dob' => 'Pedigree',
            'size' => fake()->numberBetween(10, 100),
            'ob' => fake()->numberBetween(10, 100),
            'kb' => fake()->numberBetween(10, 100),
            'link_video' => fake()->url(),
            'extra_time' => fake()->numberBetween(10, 100),
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
        ];
    }
}
