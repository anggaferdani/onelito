<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KoiStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class KoiStockController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if ($this->request->ajax()) {
            $fishes = KoiStock::query()
                ->where('status_aktif', 1)
                ->orderBy('created_at', 'desc');

            return DataTables::of($fishes)
                ->addIndexColumn()
                ->addColumn('action', 'admin.pages.fish.dt-action')
                ->editColumn('harga_ikan', function ($data) {
                    if(isset($data->harga_ikan)){
                        $number = number_format(floatval($data->harga_ikan) , 0 , '.' , '.' );
                    }else{
                        $number = "0"; // Or whatever default you want
                    }
                    return $number;
                })
                ->editColumn('foto_ikan', function ($data) {
                    $path = $data->foto_ikan ?? false;

                    if (!$path) {
                        return '';
                    }

                    return '
                    <img src="' . asset("storage/$path") . '" style="
                        max-width: 200px;
                        max-height: 200px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                    ">
                ';
                })
                ->addColumn('stock_input', function ($data) {  // Add a column for stock input field
                    return '<input type="number" class="form-control edit-stock" style="min-width: 150px !important;" data-id="' . $data->id_koi_stock . '" value="' . $data->stock . '">';
                })
                ->addColumn('weight_input', function ($data) {  // Add a column for weight input field
                    return '<input type="number" class="form-control edit-weight" style="min-width: 150px !important;" data-id="' . $data->id_koi_stock . '" value="' . $data->weight . '">';
                })
                ->rawColumns(['action', 'note', 'foto_ikan', 'stock_input', 'weight_input'])
                ->make(true);
        }

        return view('admin.pages.fish.index')->with([
            'type_menu' => 'manage-fish'
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();

        // Check if 'harga_ikan' exists and is not null before processing
        if (isset($data['harga_ikan'])) {
            $data['harga_ikan'] = str_replace('.', '', $data['harga_ikan']);
        } else {
            $data['harga_ikan'] = 0; // Set a default value if missing
        }

        // Check if 'point' exists and is not null before processing
        if (isset($data['point'])) {
            $data['point'] = str_replace('.', '', $data['point']);
        } else {
            $data['point'] = 0; // Set a default value if missing
        }

        $data['status_aktif'] = 1;

        $image = null;
        if ($this->request->hasFile('path_foto')) {
            $image = $this->request->file('path_foto')->store(
                'foto_koi_stock',
                'public'
            );
        }

        $data['foto_ikan'] = $image;
        unset($data['path_foto']);

        $createFish = KoiStock::create($data);

        if ($createFish) {
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Ikan',

            ], 200);
        } else {
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Ikan'
            ], 500);
        }
    }

    // public function show($id)
    // {
    //     $fish = KoiStock::findOrFail($id);
    //     $fish->harga = number_format($fish->harga, 0, '.', '.');
    //     $fish->point = number_format($fish->point, 0, '.', '.');

    //     if ($fish) {
    //         return response()->json($fish);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data Not Found'
    //         ], 404);
    //     }
    // }

    public function show($id)
    {
        $fish = KoiStock::findOrFail($id);
        $fish->harga = isset($fish->harga) ? number_format(floatval($fish->harga) , 0 , '.' , '.' ) : "0";
        $fish->point = isset($fish->point) ? number_format(floatval($fish->point) , 0 , '.' , '.' ) : "0";

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
        $fish = KoiStock::findOrFail($id);
        $data = $this->request->all();

        $validator = Validator::make($this->request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $image = $fish->foto_ikan;
        if ($this->request->hasFile('path_foto')) {
            $image = $this->request->file('path_foto')->store(
                'foto_koi_stock',
                'public'
            );
        }

        $data['foto_ikan'] = $image;
        unset($data['path_foto']);

        // Map form fields to database columns
        $updateData = [
            'no_ikan' => $data['no_ikan'] ?? null, // Access it with the actual db key
            'variety' => $data['variety'] ?? null, // Access it with the actual db key
            'breeder' => $data['breeder'] ?? null,
            'bloodline' => $data['bloodline'] ?? null,
            'sex' => $data['sex'] ?? null,
            'dob' => $data['dob'] ?? null,
            'size' => $data['size'] ?? null,
            'weight' => $data['weight'] ?? null,
            'height' => $data['height'] ?? null,
            'length' => $data['length'] ?? null,
            'width' => $data['width'] ?? null,
            'point' => isset($data['point']) ? str_replace('.', '', $data['point']) : 0,
            'stock' => $data['stock'] ?? null,
            'harga_ikan' => isset($data['harga_ikan']) ? str_replace('.', '', $data['harga_ikan']) : 0,
            'note' => $data['note'] ?? null,
            'link_video' => $data['link_video'] ?? null,
            'update_by' => Auth::guard('admin')->id(),
        ];

        $updateFish = $fish->update($updateData);  // Update with correctly mapped keys

        if ($updateFish) {
            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data ikan',
                    'type' => 'success'
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => [
                    'title' => 'Gagal',
                    'content' => 'Mengubah data ikan',
                    'type' => 'error'
                ],
            ], 400);
        }
    }

    public function destroy($id)
    {
        $koi = KoiStock::findOrFail($id);
        $koi->status_aktif = 0;

        $koi->save();

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function updateStock(Request $request, KoiStock $fish)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $fish->stock = $request->stock;
            $fish->save();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => $fish->stock // Return the updated stock value
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock. Please try again later.',
            ], 500);
        }
    }

    public function updateWeight(Request $request, KoiStock $fish)
    {
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $fish->weight = $request->weight;
            $fish->save();

            return response()->json([
                'success' => true,
                'message' => 'Weight updated successfully',
                'data' => $fish->weight // Return the updated weight value
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update weight. Please try again later.',
            ], 500);
        }
    }
}