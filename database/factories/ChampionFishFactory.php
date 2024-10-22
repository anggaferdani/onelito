<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChampionFish>
 */
class ChampionFishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama_champion' => fake()->name('female'),
            'tahun' => fake()->year(),
            'size' => fake()->numerify('##'),
        ];
    }
}
