<?php

namespace Database\Factories;

use App\Models\EventFish;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogBid>
 */
class LogBidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_peserta' => fn() => Member::factory()->create()->id_peserta,
            'id_ikan_lelang' => fn() => EventFish::factory()->create()->id_ikan,
            'nominal_bid' => fake()->numerify('########'),
            'auto_bid' => fake()->numerify('########'),
            'waktu_bid' => fake()->dateTimeBetween('-7 days'),
            'status_aktif' => 1,
            'create_by' => 0,
            'update_by' => null,
            'delete_by' => null,
            'delete_at' => null,
        ];
    }
}
