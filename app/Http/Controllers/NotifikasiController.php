<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function notifikasiUpdate(Request $request) {
        $notification = Notification::find($request->notification_id);

        if ($notification) {
            $notification->update([
                'status' => 0,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function notifikasi() {
        $notifications = Notification::where(function($query) {
            $query->whereNull('peserta_id')
                  ->orWhere('peserta_id', auth()->user()->id_peserta);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('new.pages.profile.notifikasi', compact(
            'notifications',
        ));
    }
}
