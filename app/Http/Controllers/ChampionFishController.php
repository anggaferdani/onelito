<?php

namespace App\Http\Controllers;

use App\Models\ChampionFish;

class ChampionFishController extends Controller
{
    public function index()
    {
        $championFishes = ChampionFish::where('status_aktif', 1)
            ->orderByRaw('created_at desc')
            ->get();

        return view('detail_koichampion',[
            "title" => "home",
            'championFishes' => $championFishes,
        ]);
    }
}
