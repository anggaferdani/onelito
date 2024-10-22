<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $data = Currency::query()
                ->where('status', 1)
                ->orderBy('name', 'asc');

            return DataTables::of($data)->addIndexColumn()->addColumn('action','admin.pages.currency.dt-action')->make(true);
        }

        return view('admin.pages.currency.index')->with([
            'type_menu' => 'manage-currency'
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $createCurrency = Currency::create($data);

        if($createCurrency){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Mata Uang',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Mata Uang'
            ],500);
        }
    }

    public function show($id)
    {
        $data = Currency::findOrFail($id);

        if($data){
            return response()->json($data);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $currency = Currency::findOrFail($id);
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), []);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $currency->update($data);

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah Data Mata Uang',
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
        $data = Currency::findOrFail($id);
        $data->status = 0;

        $data->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}
