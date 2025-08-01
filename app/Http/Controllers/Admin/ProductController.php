<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $products = Product::query()
                ->select('m_produk.*')
                ->with('category')
                ->with('photo')
                ->where('m_produk.status_aktif', 1)
                ->orderBy('m_produk.created_at', 'desc');

            return DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('category', function ($data) {
                return $data->category->kategori_produk ?? '';
            })
            ->editColumn('harga', function ($data) {
                $number = number_format( $data->harga , 0 , '.' , '.' );

                return $number;
            })
            ->editColumn('deskripsi', function ($data) {
                $maxLength = 150;
                $deskripsi = strip_tags($data->deskripsi);

                if (strlen($deskripsi) > $maxLength) {
                    $deskripsi = substr($deskripsi, 0, $maxLength) . '...';
                }

                return $deskripsi;
            })
            ->editColumn('photo', function ($data) {
                $path = $data->photo->path_foto ?? false;

                if (!$path) {
                    return '';
                }

                return '
                    <img src="'.asset("storage/$path").'" style="
                        max-width: 150px;
                        max-height: 150px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                    ">
                ';
            })
            ->addColumn('action','admin.pages.product.dt-action')
            ->rawColumns(['action', 'photo', 'deskripsi'])
            ->make(true);
        }

        $categories = ProductCategory::where('status_aktif', 1)->get();

        return view('admin.pages.product.index')->with([
            'type_menu' => 'manage-product',
            'categories' => $categories,
        ]);
    }

    public function getForEtalase()
    {
        $products = Product::query()
            ->with('photo')
            ->where('status_aktif', 1)
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->orderBy('created_at', 'desc')
            ->get(['id_produk', 'merek_produk', 'nama_produk']);

        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id_produk,
                'merek' => $product->merek_produk,
                'name' => $product->nama_produk,
                'photo_url' => $product->photo ? asset('storage/' . $product->photo->path_foto) : '',
            ];
        });

        return response()->json($formattedProducts);
    }

    public function updateEtalaseOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:m_produk,id_produk'
        ]);

        DB::beginTransaction();
        try {
            Product::where('status_aktif', 1)->update(['urutan' => null]);

            foreach ($request->order as $index => $productId) {
                Product::where('id_produk', $productId)->update(['urutan' => $index + 1]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Urutan etalase berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui urutan etalase.'], 500);
        }
    }

    public function updateStock(Request $request, Product $product)
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
            $product->stock = $request->stock;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => $product->stock // Return the updated stock value
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock. Please try again later.',
            ], 500);
        }
    }

    public function updateWeight(Request $request, Product $product)
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
            $product->weight = $request->weight;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Weight updated successfully',
                'data' => $product->weight // Return the updated weight value
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update weight. Please try again later.',
            ], 500);
        }
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();
        $data['point'] = str_replace('.', '', $data['point']);
        $data['harga'] = str_replace('.', '', $data['harga']);
        $data['status_aktif'] = 1;

        $image = null;
        if($this->request->hasFile('path_foto')){
            $image = $this->request->file('path_foto')->store(
                'foto_produk','public'
            );
        }

        $createProduct = DB::transaction(function () use ($data, $image){
            unset($data['path_foto']);
            $product = Product::create($data);

            if ($image !== null) {
                $fotoProduk['id_produk'] = $product->id_produk;
                $fotoProduk['path_foto'] = $image;
                $fotoProduk['create_by'] = Auth::guard('admin')->id();
                $fotoProduk['update_by'] = Auth::guard('admin')->id();
                $fotoProduk['status_aktif'] = 1;

                ProductPhoto::create($fotoProduk);
            }
        });

        if($createProduct){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Barang Produk',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Barang Produk'
            ],500);
        }
    }

    public function show($id)
    {
        $product = Product::with(['photo', 'category'])->findOrFail($id);
        $product->harga = number_format( $product->harga , 0 , '.' , '.' );
        $product->point = number_format( $product->point , 0 , '.' , '.' );

        if($product){
            return response()->json($product);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $product = Product::With('photo')->findOrFail($id);
        $data = $this->request->all();
        $data['harga'] = str_replace('.', '', $data['harga']);
        $data['point'] = str_replace('.', '', $data['point']);

        $validator = Validator::make($this->request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();

        $image = null;
        if($this->request->hasFile('path_foto')){
            $image = $this->request->file('path_foto')->store(
                'foto_produk','public'
            );
        }

        try {
            DB::transaction(function () use ($data, $image, $product){
                unset($data['path_foto']);
                $product->update($data);

                if ($image !== null) {
                    $fotoProduk['id_produk'] = $product->id_produk;
                    $fotoProduk['path_foto'] = $image;
                    $fotoProduk['create_by'] = Auth::guard('admin')->id();
                    $fotoProduk['update_by'] = Auth::guard('admin')->id();
                    $fotoProduk['status_aktif'] = 1;

                    if ($product->photo !== null) {
                        $productFoto = $product->photo;
                        $productFoto->path_foto = $image;
                        $productFoto->save();
                    } else {
                        ProductPhoto::create($fotoProduk);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data barang product',
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
        $product = Product::findOrFail($id);
        $product->status_aktif = 0;

        $product->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}