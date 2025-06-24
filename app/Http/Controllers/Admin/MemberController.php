<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Mail\EmailUserActivated;
use App\Mail\EmailVerification;
use App\Models\Member;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function index()
    {
        if ($this->request->ajax()) {
            $filterStatusEmail = $this->request->input('filter_status_email', 'all');
            $filterStatusAktif = $this->request->input('filter_status_aktif', 'all');

            $members = Member::query()
                ->when($filterStatusAktif !== 'all', function ($q) use ($filterStatusAktif) {
                    $q->where('status_aktif', $filterStatusAktif);
                })
                ->when($filterStatusEmail !== 'all', function ($q) use ($filterStatusEmail) {
                    if ($filterStatusEmail === 'verified') {
                        $q->whereNotNull('email_verified_at');
                    } else {
                        $q->whereNull('email_verified_at');
                    }
                })
                ->where('status_hapus', 0)
                ->orderBy('created_at', 'desc')
                ->with(['province', 'city', 'district', 'subdistrict']);

            return DataTables::of($members)
            ->addIndexColumn()
            ->addColumn('email_verif_url', function ($data) {
                $payload = array(
                    'id'        => $data->id_peserta,
                    'email'     => $data->email,
                    'action'       => 'email-verification',
                );

                $crypt = Crypt::encrypt($payload);

                $url = config('app.url') . "/ls/click?click=$crypt";

                return $url;
            })
            ->addColumn('action','admin.pages.member.dt-action')
            ->editColumn('province.prov_name', function ($data) {
                if ($data->province === null) {
                    return "";
                }

                return $data->province->prov_name;
            })
            ->editColumn('city.city_name', function ($data) {
                if ($data->city === null) {
                    return "";
                }

                return $data->city->city_name;
            })
            ->editColumn('district.dis_name', function ($data) {
                if ($data->district === null) {
                    return "";
                }

                return $data->district->dis_name;
            })
            ->editColumn('subdistrict.city_name', function ($data) {
                if ($data->subdistrict === null) {
                    return "";
                }

                return $data->subdistrict->subdis_name;
            })
            ->editColumn('status_phone_number_verification', function ($data) {
                if ($data->status_phone_number_verification == 1) {
                    return "Sudah verifikasi";
                }

                return "Belum verifikasi";
            })
            ->editColumn('status_aktif', function ($data) {
                if ($data->status_aktif === 1) {
                    return "Aktif";
                }

                return "Tidak Aktif";
            })
            ->rawColumns(['email_verif_url', 'action'])
            ->make(true);
        }

        $provinces = Province::get();

        return view('admin.pages.member.index')->with([
            'type_menu' => 'manage-member',
            'provinces' => $provinces,
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();

        $data['update_by'] = Auth::guard('admin')->id();
        $data['status_aktif'] = 1;

        $createMember = Member::create($data);

        if($createMember){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Peserta',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Peserta'
            ],500);
        }
    }

    public function show($id)
    {
        $member = Member::with(['province', 'city', 'district', 'subdistrict'])->findOrFail($id);

        if($member){
            return response()->json($member);
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
        $member = Member::findOrFail($id);

        if ($action === 'reset-password') {

            $member->password = 'ikankoi123';
            $member->save();

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Password berhasil di reset',
                    'type' => 'success'
                ],
            ],200);
        }

        $statusAktif = $member->status_aktif;
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), [
            'email' => 'required|email',
            'nama'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();
        $updateMember = $member->update($data);

        if($updateMember){
            if ($statusAktif === 0 && $data['status_aktif'] === "1") {
                Mail::to($member->email)->send(new EmailUserActivated($member->email));
            }

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data peserta',
                    'type' => 'success'
                ],
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => [
                    'title' => 'Gagal',
                    'content' => 'Mengubah data peserta',
                    'type' => 'error'
                ],
            ],400);
        }
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->status_aktif = 0;
        $member->status_hapus = 1;

        $member->save();

        return response()->json([
            'success' => true,
        ],200);
    }

    public function excels()
    {
            $filterStatusEmail = $this->request->input('filter_status_email', 'all');
            $filterStatusAktif = $this->request->input('filter_status_aktif', 'all');

            $filter = new stdClass;

            $filter->filter_status_email = $filterStatusEmail;
            $filter->filter_status_aktif = $filterStatusAktif;

            $export = new MembersExport($filter);

            return FacadesExcel::download($export, 'data_member.xlsx');
    }
}
