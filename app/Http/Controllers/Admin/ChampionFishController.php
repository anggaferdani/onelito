<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChampionFish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ChampionFishController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $fishes = ChampionFish::query()
                ->where('status_aktif', 1)
                ->orderBy('created_at', 'desc');

            return DataTables::of($fishes)
                ->addIndexColumn()
                ->editColumn('foto_ikan', function ($data) {
                    if (!$data->foto_ikan) {
                        return '';
                    }

                    return '<img src="' . asset('storage/' . $data->foto_ikan) . '" style="width:300px;height:300px;">';
                })
                ->addColumn('action', 'admin.pages.champion-fish.dt-action')
                ->rawColumns(['foto_ikan', 'action'])
                ->make(true);
        }

        return view('admin.pages.champion-fish.index', [
            'type_menu' => 'manage-champion-fish'
        ]);
    }

    public function store()
    {
        $this->request->validate([
            'path_foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $this->request->except('path_foto');

        $data['create_by']   = Auth::guard('admin')->id();
        $data['update_by']   = Auth::guard('admin')->id();
        $data['status_aktif'] = 1;

        if ($this->request->hasFile('path_foto')) {
            $file = $this->request->file('path_foto');

            $filename = Str::uuid() . '.webp';
            $path = 'foto_champion_koi/' . $filename;

            $manager = new ImageManager(new Driver());
            $image = $manager
                ->read($file)
                ->resize(300, 300)
                ->toWebp(80);

            Storage::disk('public')->put($path, (string) $image);

            $data['foto_ikan'] = $path;
        }

        ChampionFish::create($data);

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Sukses Menambahkan Champion Koi',
        ]);
    }

    public function show($id)
    {
        $fish = ChampionFish::findOrFail($id);
        return response()->json($fish);
    }

    public function update($id)
    {
        $fish = ChampionFish::findOrFail($id);

        $this->request->validate([
            'path_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $this->request->except('path_foto');

        $data['update_by'] = Auth::guard('admin')->id();

        if ($this->request->hasFile('path_foto')) {

            if ($fish->foto_ikan && Storage::disk('public')->exists($fish->foto_ikan)) {
                Storage::disk('public')->delete($fish->foto_ikan);
            }

            $filename = Str::uuid() . '.webp';
            $path = 'foto_champion_koi/' . $filename;

            $manager = new ImageManager(new Driver());
            $image = $manager
                ->read($this->request->file('path_foto'))
                ->resize(300, 300)
                ->toWebp(80);

            Storage::disk('public')->put($path, (string) $image);

            $data['foto_ikan'] = $path;
        }

        $fish->update($data);

        return response()->json([
            'success' => true,
            'message' => [
                'title' => 'Berhasil',
                'content' => 'Mengubah data Champion Koi',
                'type' => 'success'
            ],
        ]);
    }

    public function destroy($id)
    {
        $fish = ChampionFish::findOrFail($id);
        $fish->status_aktif = 0;
        $fish->save();

        return response()->json([
            'success' => true,
        ]);
    }
}
