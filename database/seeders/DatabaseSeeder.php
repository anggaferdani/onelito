<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ChampionFish;
use App\Models\Event;
use App\Models\EventFish;
use App\Models\KoiStock;
use App\Models\LogBid;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use Database\Factories\ProductCategoryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Member::factory()->create([
        //     'email' => 'rifqi@gmail.com',
        //     'password' => 'rifqi'
        // ]);

        // ProductCategory::factory()->create([
        //     'kategori_produk' => ProductCategory::PERLENGKAPAN_IKAN,
        // ]);

        // ProductCategory::factory()->create([
        //     'kategori_produk' => ProductCategory::MAKANAN_IKAN,
        // ]);

        $events = Event::with('auctionProducts')
            ->where('status_aktif', 1)->get();

        foreach ($events as $event) {
            $auctionProducts = $event->auctionProducts;

            if (count($auctionProducts) === 0) {
                continue;
            }

            $number = fake()->numberBetween(1, 10);

            LogBid::factory($number)->create([
                'id_ikan_lelang' => $auctionProducts->random()->id_ikan,
                'waktu_bid' => $event->tgl_mulai,
            ]);
        }



        // $orders = Order::factory(2)->create();


        // foreach ($orders as $order) {
        //     $random = fake()->numberBetween(2, 6);

        //     for ($i=0; $i <= $random; $i++) {
        //         OrderDetail::factory()->create([
        //             'id_order' => $order->id_order,
        //             'tanggal' => $order->tanggal,
        //             'created_at' => $order->created_at,
        //             'updated_at' => $order->created_at,
        //         ]);
        //     }

        //     $details = $order->details;

        //     $order->total = $details->sum('total');
        //     $order->save();
        // }

        // Admin::factory()->create([
        //     'username' => 'admin',
        //     'password' => 'admin2022'
        // ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
