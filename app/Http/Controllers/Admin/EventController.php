<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventFish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $auctions = Event::query()
                ->where('status_aktif', 1)
                ->orderBy('created_at', 'desc');

            return DataTables::of($auctions)
            ->addIndexColumn()
            ->editColumn('banner', function ($data) {
                $path = $data->banner ?? false;

                if (!$path) {
                    return '';
                }

                return '
                    <img src="'.asset("storage/$path").'" style="
                    width: 80px;
                    height: 80px;
                    object-fit: cover;">
                ';
            })
            ->editColumn('total_hadiah', function ($data) {
                $number = number_format( $data->total_hadiah , 0 , '.' , '.' );

                return $number;
            })
            ->addColumn('text_status_tutup', function ($data) {
                $text = "Ya";

                if ($data->status_tutup === 0) {
                    $text = "Tidak";
                }

                return $text;
            })
            ->addColumn('action','admin.pages.auction.dt-action')
            ->rawColumns(['action', 'banner', 'rules_event', 'text_status_tutup'])
            ->make(true);
        }

        $auctionProducts = EventFish::where('status_aktif', 1)->get();
        $auctionProductsNoEvent = $auctionProducts->whereNull('id_event');

        return view('admin.pages.auction.index')->with([
            'type_menu' => 'manage-auction',
            'auctionProducts' => $auctionProducts,
            'auctionProductsNoEvent' => $auctionProductsNoEvent
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();
        $data['total_hadiah'] = (int) str_replace('.', '', $data['total_hadiah']);
        $data['status_aktif'] = 1;

        $image = null;
        if($this->request->hasFile('banner')){
            $image = $this->request->file('banner')->store(
                'foto_auction','public'
            );
        }

        $data['banner'] = $image;

        $auctionProductIds = $data['auction_products'];
        unset($data['auction_products']);

        $createAuction = Event::create($data);

        EventFish::whereIn('id_ikan', $auctionProductIds)->update([
            'id_event' => $createAuction->id_event
        ]);

        if($createAuction){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Auction',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Auction'
            ],500);
        }
    }

    public function show($id)
    {
        $auction = Event::with('auctionProducts.photo')->findOrFail($id);

        if($auction){
            return response()->json($auction);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $action = $this->request->input('action', null);
        $auction = Event::With('auctionProducts')->findOrFail($id);

        if ($action === 'close-auction') {
            $auction->status_tutup = 1;
            $auction->save();

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data auction',
                    'type' => 'success'
                ],
            ],200);
        }

        $data = $this->request->all();
        $data['total_hadiah'] = (int) str_replace('.', '', $data['total_hadiah']);
        $validator = Validator::make($this->request->all(), [

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();
        $existsProductIds = $auction->auctionProducts->pluck('id_ikan')->toArray();
        $auctionProductIds = $data['edit_auction_products'];
        $removeProductIds = array_diff($existsProductIds, $auctionProductIds);
        unset($data['edit_auction_products']);

        $image = $auction->banner;
        if($this->request->hasFile('banner')){
            $image = $this->request->file('banner')->store(
                'foto_auction','public'
            );
        }

        $data['banner'] = $image;

        try {
            DB::transaction(function () use ($data, $auction, $auctionProductIds, $removeProductIds){
                $auction->update($data);

                EventFish::whereIn('id_ikan', $auctionProductIds)->update([
                    'id_event' => $auction->id_event
                ]);

                //removed item
                EventFish::whereIn('id_ikan', $removeProductIds)->update([
                    'id_event' => null
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data auction',
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
        $auction = Event::findOrFail($id);
        $auction->status_aktif = 0;

        $auction->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}
