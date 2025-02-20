<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request) {
        $query = ProductCategory::where('status_aktif', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kategori_produk', 'like', '%' . $search . '%');
            });
        }

        $kategoris = $query->latest()->paginate(10);

        return view('admin.pages.new.kategori.index', compact(
            'kategoris',
        ))->with(['type_menu' => 'kategori']);
    }

    public function create() {}

    public function store(Request $request) {
        try {
            $request->validate([
                'kategori_produk' => 'required',
            ]);

            $array = [
                'kategori_produk' => $request['kategori_produk'],
            ];

            ProductCategory::create($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        try {
            $kategori = ProductCategory::find($id);
    
            $request->validate([
                'kategori_produk' => 'required',
            ]);
    
            $array = [
                'kategori_produk' => $request['kategori_produk'],
            ];
    
            $kategori->update($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $kategori = ProductCategory::find($id);

            $kategori->update([
                'status_aktif' => 0,
            ]);

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
