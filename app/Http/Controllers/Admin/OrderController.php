<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $orders = Order::query()
                ->whereHas('details', fn($q) => $q->whereIn('productable_type', [OrderDetail::Product, OrderDetail::KoiStock]))
                ->select('t_order.*')
                ->with(['details.productable', 'latestDetail.member', 'latestDetail.productable'])
                ->where('t_order.status_aktif', 1)
                ->orderBy('t_order.created_at', 'desc');

            return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('action','admin.pages.order.dt-action')
            ->make(true);
        }

        return view('admin.pages.order.index')->with([
            'type_menu' => 'manage-order'
        ]);
    }

    public function show($id)
    {
        $order = Order::with([
        'details.productable',
        'latestDetail.member'
        ])->findOrFail($id);

        if($order){
            return response()->json($order);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $order = Order::findOrFail($id);
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {

            $order->update($data);

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data Pembelian Store',
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
        $order = Order::findOrFail($id);
        $order->status_aktif = 0;

        $order->details()->update([
            'status_aktif' => 0,
        ]);

        $order->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}
