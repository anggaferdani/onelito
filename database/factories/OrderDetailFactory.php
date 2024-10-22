<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_order' => fn() => Order::factory()->create()->id_order,
            'id_peserta' => fn() => Member::factory()->create()->id_peserta,
            'id_produk' => fn() => Product::factory()->create()->id_produk,
            'jumlah_produk' => fake()->numberBetween(1, 5),
            'total' => fake()->randomElement([1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 1000]),
            'tanggal' => fake()->dateTimeBetween('-7 days'),
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
        ];
    }
}
