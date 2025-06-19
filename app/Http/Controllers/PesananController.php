<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('status_aktif', 1)->orderBy('created_at', 'DESC');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function($row){
                    return Carbon::parse($row->tanggal)->format('Y M d H:i:s');
                })
                ->addColumn('total_tagihan', function($row){
                    $totalTagihanFormatted = 'Rp. ' . number_format($row->total_tagihan, 0, '.', '.');
                    if ($row->coin_yang_digunakan > 0) {
                        $totalTagihanFormatted .= '<div class="text-success small">(Potongan Onelito Coins Rp. ' . number_format($row->coin_yang_digunakan, 0, '.', '.') . ')</div>';
                    }
                    return $totalTagihanFormatted;
                })
                ->addColumn('opsi_pengiriman', function($row){
                    $status = '';
                    if ($row->opsi_pengiriman == 'otomatis') {
                        $status = '<span class="badge badge-primary">Otomatis</span>';
                    } elseif ($row->opsi_pengiriman == 'manual') {
                        $status = '<span class="badge badge-danger">Manual</span>';
                    }
                    return $status;
                })
                ->addColumn('status', function($row){
                    $status = '';
                    if ($row->status_order == 'pending') {
                        $status = '<span class="badge badge-secondary">Menunggu Pembayaran</span>';
                    } elseif ($row->status_order == 'paid') {
                        $status = '<span class="badge badge-primary">Menunggu Konfirmasi</span>';
                    } elseif ($row->status_order == 'process') {
                        $status = '<span class="badge badge-warning">Sedang Diproses</span>';
                    } elseif ($row->status_order == 'delivered') {
                        $status = '<span class="badge badge-info">Sedang Dalam Pengiriman</span>';
                    } elseif ($row->status_order == 'done' && $row->opsi_pengiriman == 'manual') {
                        $status = '<span class="badge badge-success">Selesai</span>';
                    } elseif ($row->status_order == 'done' && $row->done == 0) {
                        $status = '<span class="badge badge-info">Pesanan telah sampai ditujuan</span>';
                    } elseif ($row->status_order == 'done' && $row->done == 1) {
                        $status = '<span class="badge badge-success">Selesai</span>';
                    } elseif ($row->status_order == 'cancel' && $row->dibatalkan_pembeli == 0) {
                        $status = '<span class="badge badge-danger">Dibatalkan System</span>';
                    } elseif ($row->status_order == 'cancel' && $row->dibatalkan_pembeli == 2) {
                        $status = '<span class="badge badge-danger">Dibatalkan Admin dengan konfirmasi</span>';
                    } elseif ($row->status_order == 'cancel' && $row->dibatalkan_pembeli == 1) {
                        $status = '<span class="badge badge-danger">Dibatalkan Pembeli</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<div class="d-flex" style="gap: 5px !important;">';
                    $actionBtn .= '<a href="' . route('admin.pesanan.detail', $row->no_order) . '" class="btn btn-sm text-nowrap btn-secondary"><i class="fa-solid fa-eye"></i></a>';

                    if($row->status_order == 'pending') {
                        $actionBtn .= ' <button class="btn btn-sm text-nowrap btn-danger" data-toggle="modal" data-target="#batalkan-pesanan' . $row->id_order . '">Batal Pesanan</button>';
                    } elseif($row->status_order == 'paid') {
                        $actionBtn .= ' <a href="' . route('admin.pesanan.process', $row->no_order) . '" class="btn btn-sm text-nowrap btn-warning delete2" data-title="Proses Pesanan" data-text="Apakah Anda yakin ingin memproses pesanan ini?" data-confirm-button="Ya, proses sekarang" data-confirm-button-class="btn text-nowrap btn-warning">Proses Pesanan</a>';
                    } elseif($row->status_order == 'process') {
                        if ($row->opsi_pengiriman == 'otomatis') {
                            $actionBtn .= ' <a href="' . route('admin.pesanan.kirim', $row->no_order) . '" class="btn btn-sm text-nowrap btn-primary delete2" data-title="Kirim Pesanan" data-text="Apakah Anda yakin ingin mengirim pesanan ini?" data-confirm-button="Ya, kirim sekarang" data-confirm-button-class="btn text-nowrap btn-primary">Kirim Pesanan</a>';
                        } elseif ($row->opsi_pengiriman == 'manual') {
                            $actionBtn .= ' <a href="' . route('admin.pesanan.done', $row->no_order) . '" class="btn btn-sm text-nowrap btn-warning delete2" data-title="Selesaikan Pesanan" data-text="Apakah Anda yakin ingin mengirim pesanan ini?" data-confirm-button="Ya, Selesaikan sekarang" data-confirm-button-class="btn text-nowrap btn-primary">Selesaikan Pesanan</a>';
                        }
                    } else {
                        if ($row->opsi_pengiriman == 'otomatis') {
                            $actionBtn .= ' <a href="javascript:void(0)" class="btn btn-sm text-nowrap btn-info lacak-pengiriman-btn" data-toggle="modal" data-target="#lacak-pengiriman' . $row->id_order . '" data-tracking-id="' . $row->tracking_id . '" data-id_order="' . $row->id_order . '"><i class="fa-solid fa-truck"></i></a>';
                        }
                    }
                    $actionBtn .= '</div>';

                    return $actionBtn;
                })
                ->rawColumns(['total_tagihan', 'opsi_pengiriman', 'status', 'action'])
                ->make(true);
        }

        $orders = Order::where('status_aktif', 1)->get();
        return view('admin.pages.new.pesanan.index', compact('orders'))->with(['type_menu' => 'order']);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function detail($no_order) {
        try {
            $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

            if ($order) {
                return view('admin.pages.new.pesanan.detail', compact(
                    'order',
                ))->with(['type_menu' => 'order']);
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
