<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $banneres = Banner::query()
                ->orderBy('created_at', 'desc');

            return DataTables::of($banneres)
            ->addIndexColumn()
            ->editColumn('banner', function ($data) {
                $path = $data->banner ?? false;

                if (!$path) {
                    return '';
                }

                return '
                    <img src="'.asset("storage/$path").'" style="
                    width: 700px;
                    height: 150px;
                    ">
                ';
            })
            ->addColumn('action','admin.pages.banner.dt-action')
            ->rawColumns(['action', 'banner'])
            ->make(true);
        }

        return view('admin.pages.banner.index')->with([
            'type_menu' => 'manage-banner'
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $image = null;
        if($this->request->hasFile('banner')){
            $image = $this->request->file('banner')->store(
                'foto_banner','public'
            );
        }

        $data['banner'] = $image;

        $createFish = Banner::create($data);

        if($createFish){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Banner',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Banner'
            ],500);
        }
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);

        if($banner){
            return response()->json($banner);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $banner = Banner::findOrFail($id);
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $image = $banner->banner;
        if($this->request->hasFile('banner')){
            $image = $this->request->file('banner')->store(
                'foto_banner','public'
            );
        }

        try {

            $data['banner'] = $image;
            unset($data['banner']);
            $banner->update($data);

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data Banner',
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
        $banner = Banner::findOrFail($id);

        $banner->delete();

        return response()->json([
            'success' => true,
        ],200);
    }
}
