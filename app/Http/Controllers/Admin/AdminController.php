<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $admins = Admin::query()
                ->where('status_aktif', 1);

            return DataTables::of($admins)
            ->addIndexColumn()
            ->addColumn('action','admin.pages.admin.dt-action')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.pages.admin.index')->with([
            'type_menu' => 'manage-admin'
        ]);
    }
    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();

        $data['update_by'] = Auth::guard('admin')->id();
        $data['status_aktif'] = 1;

        $createAdmin = Admin::create($data);

        if($createAdmin){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Admin',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Admin'
            ],500);
        }
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id);

        if($admin){
            return response()->json($admin);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $admin = Admin::findOrFail($id);
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), [
            'email' => 'required|email',
            'nama'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();
        $updateAdmin = $admin->update($data);

        if($updateAdmin){
            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data admin',
                    'type' => 'success'
                ],
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => [
                    'title' => 'Gagal',
                    'content' => 'Mengubah data admin',
                    'type' => 'error'
                ],
            ],400);
        }
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->status_aktif = 0;

        $admin->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}
