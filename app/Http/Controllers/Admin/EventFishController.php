<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\EventFish;
use App\Models\FishPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EventFishController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $fishes = EventFish::query()
                ->with('photo')
                ->with('currency')
                ->where('status_aktif', 1)
                ->orderBy('created_at', 'desc');

            return DataTables::of($fishes)
                ->addIndexColumn()
                ->editColumn('photo', function ($data) {
                    $path = $data->photo->path_foto ?? false;

                    if (!$path) {
                        return '';
                    }

                    return '
                        <img src="'.asset("storage/$path").'" style="
                            max-width: 200px;
                            max-height: 200px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                        ">
                    ';
                })
                ->editColumn('ob', function ($data) {
                    $number = number_format($data->ob, 0, '.', '.');
                    $harga = $data->currency->symbol . ' ' . $number;
                    return $harga;
                })
                ->editColumn('kb', function ($data) {
                    $number = number_format($data->kb, 0, '.', '.');
                    $harga = $data->currency->symbol . ' ' . $number;
                    return $harga;
                })
                ->addColumn('action', 'admin.pages.auction-product.dt-action')
                ->rawColumns(['action', 'photo', 'note'])
                ->make(true);
        }

        $currencies = Currency::where('status', 1)->get();

        return view('admin.pages.auction-product.index')->with([
            'type_menu' => 'manage-auction-product',
            'currencies' => $currencies,
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();
        $data['ob'] = str_replace('.', '', $data['ob']);
        $data['kb'] = str_replace('.', '', $data['kb']);
        $data['status_aktif'] = 1;

        $image = null;
        if ($this->request->hasFile('path_foto')) {
            $image = $this->request->file('path_foto')->store(
                'foto_ikan',
                'public'
            );
        }

        $createFish = DB::transaction(function () use ($data, $image) {
            unset($data['path_foto']);
            $fish = EventFish::create($data);

            if ($image !== null) {
                $fotoIkan['id_ikan'] = $fish->id_ikan;
                $fotoIkan['path_foto'] = $image;
                $fotoIkan['create_by'] = Auth::guard('admin')->id();
                $fotoIkan['update_by'] = Auth::guard('admin')->id();
                $fotoIkan['status_aktif'] = 1;

                FishPhoto::create($fotoIkan);
            }
        });

        if ($createFish) {
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Barang Lelang',

            ], 200);
        } else {
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Barang Lelang'
            ], 500);
        }
    }

    public function show($id)
    {
        $fish = EventFish::with(['photo', 'currency'])->findOrFail($id);
        $fish->ob = number_format( $fish->ob , 0 , '.' , '.' );
        $fish->kb = number_format( $fish->kb , 0 , '.' , '.' );

        if ($fish) {
            return response()->json($fish);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ], 404);
        }
    }

    public function update($id)
    {
        $fish = EventFish::With('photo')->findOrFail($id);
        $data = $this->request->all();
        $data['ob'] = str_replace('.', '', $data['ob']);
        $data['kb'] = str_replace('.', '', $data['kb']);
        $validator = Validator::make($this->request->all(), []);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();

        $image = null;
        if ($this->request->hasFile('path_foto')) {
            $image = $this->request->file('path_foto')->store(
                'foto_ikan',
                'public'
            );
        }

        try {
            DB::transaction(function () use ($data, $image, $fish) {
                unset($data['path_foto']);
                $fish->update($data);

                if ($image !== null) {
                    $fotoIkan['id_ikan'] = $fish->id_ikan;
                    $fotoIkan['path_foto'] = $image;
                    $fotoIkan['create_by'] = Auth::guard('admin')->id();
                    $fotoIkan['update_by'] = Auth::guard('admin')->id();
                    $fotoIkan['status_aktif'] = 1;

                    if ($fish->photo !== null) {
                        $fishFoto = $fish->photo;
                        $fishFoto->path_foto = $image;
                        $fishFoto->save();
                    } else {
                        FishPhoto::create($fotoIkan);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data barang lelang',
                    'type' => 'success'
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $eventFish = EventFish::findOrFail($id);
        $eventFish->status_aktif = 0;

        $eventFish->save();

        return response()->json([
            'success' => true,
        ], 200);
    }
}
