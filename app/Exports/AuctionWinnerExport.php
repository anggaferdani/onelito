<?php

namespace App\Exports;

use App\Models\AuctionWinner;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AuctionWinnerExport implements FromView
{
    
    public function view(): View
    {
        $winners = AuctionWinner::query()
            ->join('t_log_bidding', 't_pemenang_lelang.id_bidding', '=', 't_log_bidding.id_bidding')
            ->join('m_ikan_lelang', 't_log_bidding.id_ikan_lelang', '=', 'm_ikan_lelang.id_ikan')
            ->select(
                'm_ikan_lelang.id_event as id_event',
            't_log_bidding.id_peserta as id_peserta'
            )
            ->with(['member.city', 'event'])
            ->where('t_pemenang_lelang.status_aktif', 1)
            ->groupBy('id_peserta', 'id_event')
            ->get()
            ->sortByDesc('id_event')
            ;

        return view('admin.pages.auction-winner.excel', [
            'winners' => $winners
        ]);
    }
}
