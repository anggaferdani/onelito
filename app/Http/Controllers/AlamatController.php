<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function index(Request $request) {
        $auth = Auth::guard('member')->user();

        $query = Alamat::where('peserta_id', $auth->id_peserta)->where('status', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', '%' . $search . '%')
                  ->orWhere('alamat_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        $alamats = $query->latest()->paginate(3);

        return view('new.pages.profile.alamat', compact(
            'alamats',
        ));
    }

    public function create() {}

    public function store(Request $request) {
        $request->validate([
            'label' => 'required',
            'nama_penerima' => 'required',
            'no_hp' => 'required',
            'alamat_lengkap' => 'required',
        ]);

        $auth = Auth::guard('member')->user();

        try {
            $array = [
                'peserta_id' => $auth->id_peserta,
                'label' => $request['label'],
                'nama_penerima' => $request['nama_penerima'],
                'no_hp' => $request['no_hp'],
                'alamat_lengkap' => $request['alamat_lengkap'],
            ];

            Alamat::create($array);
    
            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        $alamat = Alamat::find($id);

        $request->validate([
            'label' => 'required',
            'nama_penerima' => 'required',
            'no_hp' => 'required',
            'alamat_lengkap' => 'required',
        ]);

        try {
            $array = [
                'label' => $request['label'],
                'nama_penerima' => $request['nama_penerima'],
                'no_hp' => $request['no_hp'],
                'alamat_lengkap' => $request['alamat_lengkap'],
            ];

            $alamat->update($array);
    
            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {
        $auth = Auth::guard('member')->user();

        try {
            $peserta = Member::find($auth->id_peserta);
            $alamat = Alamat::find($id);

            if ($auth->pilih_alamat == $alamat->id) {
                $peserta->update([
                    'pilih_alamat' => null,
                ]);
            }

            if ($auth->alamat_utama == $alamat->id) {
                $peserta->update([
                    'alamat_utama' => null,
                ]);
            }

            $alamat->update([
                'status' => 2,
            ]);

            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function pilihAlamat($alamatId) {
        $auth = Auth::guard('member')->user();

        try {
            $peserta = Member::find($auth->id_peserta);

            $peserta->update([
                'pilih_alamat' => $alamatId,
            ]);

            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function alamatUtama($alamatId) {
        $auth = Auth::guard('member')->user();

        try {
            $peserta = Member::find($auth->id_peserta);

            $peserta->update([
                'alamat_utama' => $alamatId,
            ]);

            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}