<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventFish;
use Illuminate\Http\Request;
use App\Jobs\SendAuctionReminder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendAuctionWinnerNotification;

class EventController extends Controller
{
    public function __construct(Request $request){
        $this->request = $request;
    }
    
    public function index()
    {
        if ($this->request->ajax()) {
            $auctions = Event::query()
                ->where('status_aktif', 1)
                ->orderBy('created_at', 'desc');

            return DataTables::of($auctions)
            ->addIndexColumn()
            ->editColumn('tgl_mulai', function ($data) {
                // Gunakan accessor di sini juga
                return $data->tgl_mulai_wib;
            })
            ->editColumn('tgl_akhir', function ($data) {
                // Gunakan accessor di sini juga
                return $data->tgl_akhir_wib;
            })
            ->editColumn('banner', function ($data) {
                $path = $data->banner ?? false;

                if (!$path) {
                    return '';
                }

                return '
                    <img src="'.asset("storage/$path").'" style="
                        max-width: 400px;
                        max-height: 150px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                    ">
                ';
            })
            ->editColumn('total_hadiah', function ($data) {
                $number = number_format( $data->total_hadiah , 0 , '.' , '.' );
                return $number;
            })
            ->editColumn('rules_event', function ($data) {
                // Konversi dan bersihkan rules_event saat ditampilkan
                $rules_event = mb_convert_encoding($data->rules_event, 'UTF-8', mb_detect_encoding($data->rules_event));
                $rules_event = preg_replace('/[\x00-\x1F\x7F]/u', '', $rules_event);
                $maxLength = 150;
                $rules_event = strip_tags($rules_event);

                if (strlen($rules_event) > $maxLength) {
                    $rules_event = mb_substr($rules_event, 0, $maxLength, 'UTF-8') . '...';
                }

                return $rules_event;
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
        try {
            DB::beginTransaction();
            
            $data = $this->request->all();

            // Tangani rules_event
            if (isset($data['rules_event'])) {
                // Hapus BOM jika ada
                $data['rules_event'] = preg_replace('/[\x{FEFF}]/u', '', $data['rules_event']);
                
                // Deteksi encoding dan konversi ke UTF-8
                $encoding = mb_detect_encoding($data['rules_event'], ['UTF-8', 'ASCII', 'ISO-8859-1']);
                $data['rules_event'] = mb_convert_encoding($data['rules_event'], 'UTF-8', $encoding);
                
                // Bersihkan karakter non-printable
                $data['rules_event'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $data['rules_event']);
                
                // Log untuk debugging jika perlu
            }

            if (!empty($data['tgl_mulai'])) {
                // Gunakan Carbon::parse yang lebih fleksibel daripada createFromFormat
                // Dia bisa mengenali berbagai format tanggal umum secara otomatis
                $data['tgl_mulai'] = Carbon::parse($data['tgl_mulai'], 'Asia/Jakarta')->setTimezone('UTC');
            }
            if (!empty($data['tgl_akhir'])) {
                $data['tgl_akhir'] = Carbon::parse($data['tgl_akhir'], 'Asia/Jakarta')->setTimezone('UTC');
            } else {
                // Jika tgl_akhir kosong, pastikan nilainya di-set ke null agar tidak error
                $data['tgl_akhir'] = null;
            }

            $data['create_by'] = Auth::guard('admin')->id();
            $data['update_by'] = Auth::guard('admin')->id();
            $data['total_hadiah'] = (int) str_replace('.', '', $data['total_hadiah']);
            $data['status_aktif'] = 1;
            $data['notifikasi_dikirim'] = 1;

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

            DB::commit();

            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Auction'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Auction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $auction = Event::with('auctionProducts.photo')->findOrFail($id);

        if($auction){
            if ($auction->rules_event) {
                $auction->rules_event = mb_convert_encoding($auction->rules_event, 'UTF-8', mb_detect_encoding($auction->rules_event));
            }

            $responseData = $auction->toArray();

            $responseData['tgl_mulai_wib'] = $auction->tgl_mulai_wib;
            $responseData['tgl_akhir_wib'] = $auction->tgl_akhir_wib;
            
            return response()->json($responseData);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data Not Found'
        ],404);
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();

            $action = $this->request->input('action', null);
            $auction = Event::with('auctionProducts')->findOrFail($id);

            if ($action === 'close-auction') {
                $auction->status_tutup = 1;
                $auction->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => [
                        'title' => 'Berhasil',
                        'content' => 'Lelang berhasil ditutup',
                        'type' => 'success',
                    ],
                ], 200);
            }

            $data = $this->request->all();

            if (isset($data['rules_event'])) {
                $data['rules_event'] = preg_replace('/[\x{FEFF}]/u', '', $data['rules_event']);
                $encoding = mb_detect_encoding($data['rules_event'], ['UTF-8', 'ASCII', 'ISO-8859-1']);
                $data['rules_event'] = mb_convert_encoding($data['rules_event'], 'UTF-8', $encoding);
                $data['rules_event'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $data['rules_event']);
            }

            if (!empty($data['tgl_mulai'])) {
                $data['tgl_mulai'] = Carbon::parse($data['tgl_mulai'], 'Asia/Jakarta')->setTimezone('UTC');
            }
            if (!empty($data['tgl_akhir'])) {
                $data['tgl_akhir'] = Carbon::parse($data['tgl_akhir'], 'Asia/Jakarta')->setTimezone('UTC');
            } else {
                $data['tgl_akhir'] = null;
            }

            $data['total_hadiah'] = (int) str_replace('.', '', $data['total_hadiah']);
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

            $auction->update($data);

            EventFish::whereIn('id_ikan', $auctionProductIds)->update([
                'id_event' => $auction->id_event
            ]);

            EventFish::whereIn('id_ikan', $removeProductIds)->update([
                'id_event' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data auction',
                    'type' => 'success'
                ],
            ],200);

        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy($id)
    {
        try {
            $auction = Event::findOrFail($id);
            $auction->status_aktif = 0;
            $auction->save();

            return response()->json([
                'success' => true,
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
}