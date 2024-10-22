<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChampionFish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

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
                $path = $data->foto_ikan ?? false;

                if (!$path) {
                    return '';
                }

                return '
                    <img src="'.asset("storage/$path").'" style="
                    width: 300px;
                    height: 300px;
                    ">
                ';
            })
            ->addColumn('action','admin.pages.champion-fish.dt-action')
            ->rawColumns(['action', 'foto_ikan'])
            ->make(true);
        }

        return view('admin.pages.champion-fish.index')->with([
            'type_menu' => 'manage-champion-fish'
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();
        $data['status_aktif'] = 1;

        $image = null;
        if($this->request->hasFile('path_foto')){
            $image = $this->request->file('path_foto')->store(
                'foto_champion_koi','public'
            );
        }

        $data['foto_ikan'] = $image;
        unset($data['path_foto']);

        $createFish = ChampionFish::create($data);

        if($createFish){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Champion Koi',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Champion Koi'
            ],500);
        }
    }

    public function show($id)
    {
        $fish = ChampionFish::findOrFail($id);

        if($fish){
            return response()->json($fish);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $fish = ChampionFish::findOrFail($id);
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();

        $image = $fish->foto_ikan;
        if($this->request->hasFile('path_foto')){
            $image = $this->request->file('path_foto')->store(
                'foto_champion_koi','public'
            );
        }

        try {

            $data['foto_ikan'] = $image;
            unset($data['path_foto']);
            $fish->update($data);

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data Champion Koi',
                    'type' => 'success'
                ],
            ],200);

        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy($id)
    {
        $fish = ChampionFish::findOrFail($id);
        $fish->status_aktif = 0;

        $fish->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}
