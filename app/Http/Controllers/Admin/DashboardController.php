<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\LogBid;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = CarbonImmutable::now();

        $startOfMoth = $now->startOfMonth();
        $endOfMonth = $now->endOfMonth();

        $months = [];

        foreach ($now->startOfYear()->range($now->endOfYear(), 1, 'month') as $month) {
            $months[$month->month] = $month->monthName;
        }

        $productSoldCharts = OrderDetail::whereBetween('created_at', [$startOfMoth, $endOfMonth])
                ->selectRaw('sum(jumlah_produk) as total_produk, sum(total) as total, Date(created_at) as date')
                ->whereHas('order', fn($q) => $q->where('status', Order::SENT))
                ->where('status_aktif', 1)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy('created_at', 'DESC')->get()
                ->mapWithKeys(fn($i) => [$i['date'] => $i]);

        $thisMonthsProductSoldCharts = [];
        $thisMonthsNominalProductSoldCharts = [];
        foreach ($startOfMoth->range($endOfMonth) as $date) {
            $dateString = $date->toDateString();
            $thisMonthsProductSoldCharts['labels'][] = $date->day;
            $thisMonthsNominalProductSoldCharts['labels'][] = $date->day;
            if (!array_key_exists($dateString, $productSoldCharts->toArray())) {
                $thisMonthsProductSoldCharts['data'][] = 0;
                $thisMonthsNominalProductSoldCharts['data'][] = 0;
                continue;
            }
            $thisMonthsProductSoldCharts['data'][] = (int) $productSoldCharts[$dateString]->total_produk;
            $thisMonthsNominalProductSoldCharts['data'][] = (int) $productSoldCharts[$dateString]->total;
        }

        $countEventAuction = Event::where('kategori_event', Event::EVENT)
            ->where('status_aktif', 1)
            ->paginate()->total();

        $countRegularAuction = Event::where('kategori_event', Event::REGULAR)
            ->where('status_aktif', 1)
            ->paginate()->total();

        $countProduct = Product::where('status_aktif', 1)
            ->paginate()->total();

        $countProductSold = OrderDetail::where('status_aktif', 1)
            ->sum('jumlah_produk');

        $countProductSold = OrderDetail::where('status_aktif', 1)
            ->sum('jumlah_produk');
        // dd($thisMonthsProductSoldCharts);
        return view('admin.pages.dashboard')->with([
            'type_menu' => 'dashboard',
            'countEventAuction' => $countEventAuction,
            'countRegularAuction' => $countRegularAuction,
            'countProduct' => $countProduct,
            'countProductSold' => $countProductSold ?? 0,
            'thisMonthsProductSoldCharts' => $thisMonthsProductSoldCharts,
            'thisMonthsNominalProductSoldCharts' => $thisMonthsNominalProductSoldCharts,
            'now' => $now,
            'months' => $months,
        ]);
    }

    public function productSoldChart()
    {
        $now = Carbon::now();
        $setMonth = $this->request->input('month', $now->month);
        $date = CarbonImmutable::createFromDate(Carbon::now()->month($setMonth));

        $startOfMoth = $date->startOfMonth();
        $endOfMonth = $date->endOfMonth();

        $months = [];

        foreach ($date->startOfYear()->range($date->endOfYear(), 1, 'month') as $month) {
            $months[$month->month] = $month->monthName;
        }

        $productSoldCharts = OrderDetail::whereBetween('created_at', [$startOfMoth, $endOfMonth])
                ->selectRaw('sum(jumlah_produk) as total_produk, Date(created_at) as date')
                ->whereHas('order', fn($q) => $q->where('status', Order::SENT))
                ->where('status_aktif', 1)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy('created_at', 'DESC')->get()
                ->mapWithKeys(fn($i) => [$i['date'] => $i]);

        $thisMonthsProductSoldCharts = [];
        foreach ($startOfMoth->range($endOfMonth) as $date) {
            $dateString = $date->toDateString();
            $thisMonthsProductSoldCharts['labels'][] = $date->day;
            if (!array_key_exists($dateString, $productSoldCharts->toArray())) {
                $thisMonthsProductSoldCharts['data'][] = 0;
                continue;
            }
            $thisMonthsProductSoldCharts['data'][] = (int) $productSoldCharts[$dateString]->total_produk;
        }

        return response()->json($thisMonthsProductSoldCharts);
    }

    public function productSoldNominalChart()
    {
        $now = Carbon::now();
        $setMonth = $this->request->input('month', $now->month);
        $date = CarbonImmutable::createFromDate(Carbon::now()->month($setMonth));

        $startOfMoth = $date->startOfMonth();
        $endOfMonth = $date->endOfMonth();

        $months = [];

        foreach ($date->startOfYear()->range($date->endOfYear(), 1, 'month') as $month) {
            $months[$month->month] = $month->monthName;
        }

        $productSoldCharts = OrderDetail::whereBetween('created_at', [$startOfMoth, $endOfMonth])
                ->selectRaw('sum(total) as total, Date(created_at) as date')
                ->whereHas('order', fn($q) => $q->where('status', Order::SENT))
                ->where('status_aktif', 1)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy('created_at', 'DESC')->get()
                ->mapWithKeys(fn($i) => [$i['date'] => $i]);

        $thisMonthsProductSoldCharts = [];
        foreach ($startOfMoth->range($endOfMonth) as $date) {
            $dateString = $date->toDateString();
            $thisMonthsProductSoldCharts['labels'][] = $date->day;
            if (!array_key_exists($dateString, $productSoldCharts->toArray())) {
                $thisMonthsProductSoldCharts['data'][] = 0;
                continue;
            }
            $thisMonthsProductSoldCharts['data'][] = (int) $productSoldCharts[$dateString]->total;
        }

        return response()->json($thisMonthsProductSoldCharts);
    }

    public function auctionParticipantChart()
    {
        $now = Carbon::now();
        $setMonth = $this->request->input('month', $now->month);
        $date = CarbonImmutable::createFromDate(Carbon::now()->month($setMonth));

        $startOfMoth = $date->startOfMonth();
        $endOfMonth = $date->endOfMonth();

        $months = [];

        foreach ($date->startOfYear()->range($date->endOfYear(), 1, 'month') as $month) {
            $months[$month->month] = $month->monthName;
        }

        $auctionParticipantsReguler = LogBid::whereBetween('waktu_bid', [$startOfMoth, $endOfMonth])
                ->where('status_aktif', 1)
                ->whereHas('eventFish', fn($q) => $q->whereHas('event', fn($q2) => $q2->where('kategori_event', Event::REGULAR)))
                ->selectRaw('count(distinct id_peserta) as total, Date(waktu_bid) as date')
                ->groupBy(DB::raw('Date(waktu_bid)'))
                ->orderBy('waktu_bid', 'DESC')->get()
                ->mapWithKeys(fn($i) => [$i['date'] => $i]);

        $auctionParticipantsSpecial = LogBid::whereBetween('waktu_bid', [$startOfMoth, $endOfMonth])
            ->where('status_aktif', 1)
            ->whereHas('eventFish', fn($q) => $q->whereHas('event', fn($q2) => $q2->where('kategori_event', Event::EVENT)))
            ->selectRaw('count(distinct id_peserta) as total, Date(waktu_bid) as date')
            ->groupBy(DB::raw('Date(waktu_bid)'))
            ->orderBy('waktu_bid', 'DESC')->get()
            ->mapWithKeys(fn($i) => [$i['date'] => $i]);

        $thisMonthsAuctionParticipants = [];
        foreach ($startOfMoth->range($endOfMonth) as $date) {
            $dateString = $date->toDateString();
            $thisMonthsAuctionParticipants['regular']['labels'][] = $date->day;
            if (!array_key_exists($dateString, $auctionParticipantsReguler->toArray())) {
                $thisMonthsAuctionParticipants['regular']['data'][] = 0;
                continue;
            }
            $thisMonthsAuctionParticipants['regular']['data'][] = (int) $auctionParticipantsReguler[$dateString]->total;
        }

        foreach ($startOfMoth->range($endOfMonth) as $date) {
            $dateString = $date->toDateString();
            $thisMonthsAuctionParticipants['special']['labels'][] = $date->day;
            if (!array_key_exists($dateString, $auctionParticipantsSpecial->toArray())) {
                $thisMonthsAuctionParticipants['special']['data'][] = 0;
                continue;
            }
            $thisMonthsAuctionParticipants['special']['data'][] = (int) $auctionParticipantsSpecial[$dateString]->total;
        }

        return response()->json($thisMonthsAuctionParticipants);
    }
}
