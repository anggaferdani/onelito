<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function notifikasiUpdate(Request $request) {
        $auth = Auth::guard('member')->user();

        try {
            $peserta = Member::find($auth->id_peserta);

            $array = [
                'menunggu_pembayaran' => $request->has('menunggu_pembayaran') ? 1 : 2,
                'menunggu_konfirmasi' => $request->has('menunggu_konfirmasi') ? 1 : 2,
                'pesanan_diproses' => $request->has('pesanan_diproses') ? 1 : 2,
                'pesanan_dikirim' => $request->has('pesanan_dikirim') ? 1 : 2,
                'pesanan_selesai' => $request->has('pesanan_selesai') ? 1 : 2,
                'pengingat' => $request->has('pengingat') ? 1 : 2,
            ];

            $peserta->update($array);

            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function notifikasi() {
        return view('new.pages.profile.notifikasi');
    }
}
