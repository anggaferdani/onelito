<?php

namespace App\Http\Controllers\Admin\Bot;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\Bot\Lelang;
use App\Models\Bot\LogBidding;
use App\Models\Bot\PemenangLelang;
use Maatwebsite\Excel\Facades\Excel;

class PemenangLelangController extends Controller
{
    public function index()
    {
        // $items = LogBidding::groupBy('bid_id')->latest()->get();
        $items = DB::connection('mysql_bot')->select('SELECT DISTINCT start_time, end_time, ob, kb, judul_lelang FROM auction_results ORDER BY start_time DESC');
        // dd($items);
        // $lelangs = Lelang::with('bid.produk')->whereDate('selesai_lelang', '<', Carbon::now()->translatedFormat('Y-m-d'))->latest()->get();
        return view('admin.pages.auction-bot.pemenang-lelang.index', [
            'title' => 'Pemenang Lelang',
            'type_menu' => 'bot-winner',
            'items' => $items
        ]);
    }

    public function data_lelang($start_time)
    {
        // $items = LogBidding::groupBy('bid_id')->latest()->get();
        $items = DB::connection('mysql_bot')->select('SELECT ar.start_time, ar.end_time, ar.id, ar.judul_lelang, ar.nomor_ikan, ar.harga_akhir, m.user_id, m.full_name, m.alamat_tinggal, m.kota_tinggal, m.no_telp FROM auction_results AS ar LEFT JOIN member_table AS m ON ar.winner_user_id = m.user_id WHERE ar.start_time = :start_time ORDER BY ar.nomor_ikan DESC', [$start_time]);
        // dd($items);
        return view('admin.pages.auction-bot.pemenang-lelang.details', [
            'title' => 'Pemenang Lelang',
            'type_menu' => 'bot-winner',
            'items' => $items
        ]);
    }
    public function invoice($id)
    {
        // $items = PemenangLelang::findOrFail($id);
        // $ar = PemenangLelang::findOrFail($id);

        // $mem = Member::where('user_id', '=', $id)->select('full_name', 'alamat_tinggal', 'kota_tinggal', 'no_telp')->get();

        $items = DB::connection('mysql_bot')->select('SELECT ar.id, ar.start_time, ar.end_time, ar.judul_lelang, ar.nomor_ikan, ar.harga_akhir, m.user_id, m.full_name, m.alamat_tinggal, m.kota_tinggal, m.no_telp FROM auction_results AS ar LEFT JOIN member_table AS m ON ar.winner_user_id = m.user_id WHERE ar.id = :id', [$id]);
        // dd($items);
        return view('admin.pages.auction-bot.invoice.index', [
            'title' => 'Pemenang Lelang',
            'type_menu' => 'bot-winner',
            'items' => $items
        ]);
    }

    public function export($id){
    $loan= array();
        $loans = DB::connection('mysql_bot')->select('SELECT ar.id, ar.start_time, ar.end_time, ar.judul_lelang, ar.nomor_ikan, ar.harga_akhir, m.user_id, m.full_name, m.alamat_tinggal, m.kota_tinggal, m.no_telp FROM auction_results AS ar LEFT JOIN member_table AS m ON ar.winner_user_id = m.user_id WHERE ar.id = :id', [$id]);
        $data =  [
            'success' => 'success',
            'loans' => $loans,
    ];
    return Excel::download(new InvoiceExport($data), 'invoice.xlsx');
    }

    // public function export($id)
    // {
    //     // $items = DB::connection('mysql_bot')->select('SELECT ar.id, ar.start_time, ar.end_time, ar.judul_lelang, ar.nomor_ikan, ar.harga_akhir, m.user_id, m.full_name, m.alamat_tinggal, m.kota_tinggal, m.no_telp FROM auction_results AS ar LEFT JOIN member_table AS m ON ar.winner_user_id = m.user_id WHERE ar.id = :id', [$id]);

    //     return Excel::download(new InvoiceExport, 'invoice.xlsx');
    // }

    public function create()
    {
        $lelang = Lelang::latest()->get();
        return view('admin.pages.auction-bot.pemenang-lelang.create', [
            'title' => 'Tambah Pemenang Lelang',
            'type_menu' => 'bot-winner',
            'lelangs' => $lelang
        ]);
    }

    public function store($id)
    {
        $item = LogBidding::findOrFail($id);
        // $pemenang = PemenangLelang::where('id_lelang',request('id_lelang'))->where('nama_produk',request('nama_produk'))->where('id_telegram_user',request('id_telegram_user'))->first();
        $pemenang = PemenangLelang::where('id_lelang', request('id_lelang'))->where('nama_produk', request('nama_produk'))->where('nama_member', request('nama_member'))->first();
        if ($pemenang) {
            return redirect()->back()->with('error', 'Pemenang Lelang ' . $item->lelang->nama . ' dengan produk ' . $item->nama_produk . ' sudah ada');
        }
        PemenangLelang::create([
            // 'id_telegram_user' => $item->id_telegram_user,
            'nama_member' => $item->nama_member,
            'nama_produk' => $item->nama_produk,
            'id_lelang' => $item->id_lelang,
            'status' => 1,
            'harga' => $item->harga
        ]);
        return redirect()->route('bot-pemenang-lelang.index')->with('success', 'Pemenang Lelang berhasil disimpan');
    }

    public function show($id)
    {
        $item = PemenangLelang::findOrFail($id)->select('SELECT ar.id, ar.start_time, ar.end_time, ar.nomor_ikan, ar.judul_lelang, ar.harga_akhir, m.user_id, m.full_name, m.alamat_tinggal, m.kota_tinggal, m.no_telp FROM auction_results AS ar LEFT JOIN member_table AS m ON ar.winner_user_id = m.user_id ORDER BY ar.id ASC');
        return view('admin.pages.auction-bot.pemenang-lelang.details', [
            'title' => 'Detail Pemenang Lelang',
            'item' => $item
        ]);
    }

    public function edit($id)
    {
        // $item = DB::connection('mysql_bot')->select('SELECT * FROM auction_results LEFT JOIN member_table ON winner_user_id = user_id WHERE winner_user_id = 1');
        $item = PemenangLelang::findOrFail($id)->with('member:user_id,full_name,alamat_tinggal,kota_tinggal')->latest()->groupBy('nomor_ikan')->get();
        dd($item);

        return view('admin.pages.auction-bot.pemenang-lelang.details', [
            'title' => 'Lihat Pemenang Lelang',
            'type_menu' => 'bot-winner',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'id_lelang' => ['required'],
            'nama_produk' => ['required'],
            'harga' => ['required'],
            // 'id_telegram_user' => ['required'],
            'nama_member' => ['required'],
            'status' => ['required', 'in:0,1']
        ]);

        $data = request()->all();
        $item = PemenangLelang::findOrFail($id);
        $item->update($data);
        return redirect()->route('bot-pemenang-lelang.index')->with('success', 'Pemenang Lelang berhasil disimpan');
    }

    public function destroy($id)
    {
        $item = PemenangLelang::findOrFail($id);
        $item->delete();
        return redirect()->route('bot-pemenang-lelang.index')->with('success', 'Pemenang Lelang berhasil disimpan');
    }
}
