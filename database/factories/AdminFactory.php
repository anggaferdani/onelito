<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => fake()->name,
            'username' => fake()->userName,
            'email' => fake()->email,
            'password' => fake()->password,
            'no_hp' => fake()->phoneNumber,
            'alamat' => fake()->address,
            'kota' => fake()->city,
            'level' => 1,
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
        ];
    }
}
