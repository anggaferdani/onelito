<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventFish;
use App\Models\KoiStock;
use App\Models\LogBid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoiStockController extends Controller
{
    public function index()
    {
        $auth = Auth::guard('member')->user();

        $fishes = KoiStock::
            where('status_aktif', 1)
            ->when($auth !== null, function ($q) use ($auth){
                return $q->with([
                    'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
                );
            }, function ($q) {
                return $q;
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage());

        return view('koi_stok',[
            'auth' => $auth,
            'fishes' => $fishes,
            'title' => 'KOI STOCK'
        ]);
    }

    public function show($id)
    {
        $auth = Auth::guard('member')->user();

        $fish = KoiStock::
            where('status_aktif', 1)
            ->findOrFail($id);

        return view('detail_koistok',[
            'auth' => $auth,
            'fish' => $fish,
            'title' => 'KOI STOCK'
        ]);
    }
}
