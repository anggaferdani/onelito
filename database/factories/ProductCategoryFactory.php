<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $categories = [ProductCategory::MAKANAN_IKAN, ProductCategory::PERLENGKAPAN_IKAN, ProductCategory::IKAN];
        return [
            'kategori_produk' => fake()->randomElement($categories),
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => 0,
        ];
    }
}
