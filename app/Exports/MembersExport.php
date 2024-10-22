<?php

namespace App\Exports;

use App\Models\Member;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MembersExport implements FromView
{
    protected $filter;
    
    public function __construct(object $filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $filterStatusEmail = $this->filter->filter_status_email;
        $filterStatusAktif = $this->filter->filter_status_aktif;

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
        ->with(['province', 'city', 'district', 'subdistrict'])->get();

        return view('admin.pages.member.excel', [
            'members' => $members
        ]);
    }
}
