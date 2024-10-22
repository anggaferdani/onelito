<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'no_order' => fake()->numerify('########'),
            'tanggal' => fake()->dateTimeBetween('-30 days'),
            'total' => fake()->numerify('########'),
            'pembayaran' => null,
            'status' => fake()->randomElement(['Menunggu Dikirim', 'Dikirim', 'Selesai']),
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
            'created_at' => fake()->dateTimeBetween('-30 days'),
        ];
    }
}
