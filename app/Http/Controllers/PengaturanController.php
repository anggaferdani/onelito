<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    public function pengaturan() {
        $auth = Auth::guard('member')->user();

        $loginHistories = LoginHistory::where('peserta_id', $auth->id_peserta)->where('status', 1)->latest()->paginate(5);

        return view('new.pages.profile.pengaturan', compact(
            'loginHistories',
        ));
    }
}
