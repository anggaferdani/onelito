<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MemberFactory extends Factory
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
            'email' => fake()->email,
            'password' => fake()->password,
            'no_hp' => fake()->phoneNumber,
            'alamat' => fake()->address,
            'provinsi' => fake()->city,
            'kecamatan' => fake()->city,
            'kelurahan' => fake()->city,
            'kota' => fake()->city,
            'kode_pos' => fake()->postcode,
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
        ];
    }
}
