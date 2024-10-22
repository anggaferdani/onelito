<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductStore>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_kategori_produk' => fn () => ProductCategory::get()->random()->id_kategori_produk,
            'merek_produk' => fake()->firstName('male'),
            'nama_produk' => fake()->name('male'),
            'harga' => fake()->numerify('######'),
            'berat' => fake()->numerify('###'),
            'deskripsi' => fake()->realTextBetween(),
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
        ];
    }
}
