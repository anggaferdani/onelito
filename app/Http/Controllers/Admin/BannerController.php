<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;

class BannerController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $banners = Banner::query()->orderBy('created_at', 'desc');

            return DataTables::of($banners)
                ->addIndexColumn()
                ->editColumn('banner', function ($data) {
                    if (!$data->banner) {
                        return '';
                    }

                    return '<img src="' . asset('storage/' . $data->banner) . '" style="width:700px;height:150px;">';
                })
                ->addColumn('action', 'admin.pages.banner.dt-action')
                ->rawColumns(['banner', 'action'])
                ->make(true);
        }

        return view('admin.pages.banner.index', [
            'type_menu' => 'manage-banner'
        ]);
    }

    private function processImage($file): array
    {
        if (extension_loaded('imagick')) {
            $manager = new ImageManager(new \Intervention\Image\Drivers\Imagick\Driver());
            $filename = Str::uuid() . '.webp';
            $image = $manager->read($file)->resize(700, 150)->toWebp(80);
            return ['filename' => $filename, 'content' => (string) $image];
        }

        if (extension_loaded('gd')) {
            $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $filename = Str::uuid() . '.webp';
            $image = $manager->read($file)->resize(700, 150)->toWebp(80);
            return ['filename' => $filename, 'content' => (string) $image];
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return ['filename' => $filename, 'content' => file_get_contents($file->getRealPath())];
    }

    public function store()
    {
        $this->request->validate([
            'banner' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $this->request->all();

        if ($this->request->hasFile('banner')) {
            $file = $this->request->file('banner');
            $result = $this->processImage($file);
            $path = 'foto_banner/' . $result['filename'];

            Storage::disk('public')->put($path, $result['content']);
            $data['banner'] = $path;
        }

        Banner::create($data);

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Sukses Menambahkan Banner',
        ]);
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    public function update($id)
    {
        $banner = Banner::findOrFail($id);

        $this->request->validate([
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $this->request->except('banner');

        if ($this->request->hasFile('banner')) {

            if ($banner->banner && Storage::disk('public')->exists($banner->banner)) {
                Storage::disk('public')->delete($banner->banner);
            }

            $result = $this->processImage($this->request->file('banner'));
            $path = 'foto_banner/' . $result['filename'];

            Storage::disk('public')->put($path, $result['content']);
            $data['banner'] = $path;
        }

        $banner->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update banner',
        ]);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->banner && Storage::disk('public')->exists($banner->banner)) {
            Storage::disk('public')->delete($banner->banner);
        }

        $banner->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
