<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function notifikasiUpdate(Request $request)
    {
        $auth = Auth::guard('member')->user();

        if ($request->type === 'personal') {
            $notification = Notification::find($request->notification_id);
            if ($notification && $notification->peserta_id == $auth->id_peserta) {
                $notification->update(['status' => 0]);
            }
        } elseif ($request->type === 'system') {
            $sysNotif = SystemNotification::find($request->notification_id);

            if ($sysNotif) {
                $notif = Notification::firstOrCreate(
                    [
                        'system_notification_id' => $sysNotif->id,
                        'peserta_id' => $auth->id_peserta,
                    ],
                    [
                        'status' => 0,
                    ]
                );

                if ($notif->status == 1) {
                    $notif->update(['status' => 0]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function notifikasi()
    {
        $auth = Auth::guard('member')->user();

        $systemNotifications = \App\Models\SystemNotification::where('status', 1)->get();

        $personalNotifications = \App\Models\Notification::where('peserta_id', $auth->id_peserta)->get();

        $notifications = collect();

        foreach ($systemNotifications as $sys) {
            $personal = $personalNotifications->firstWhere('system_notification_id', $sys->id);
            $notifications->push((object)[
                'id' => $sys->id,
                'label' => $sys->label,
                'description' => $sys->description,
                'link' => $sys->link,
                'created_at' => $sys->created_at,
                'status' => $personal ? 0 : 1,
                'type' => 'system',
            ]);
        }

        foreach ($personalNotifications->whereNull('system_notification_id') as $notif) {
            $notifications->push((object)[
                'id' => $notif->id,
                'label' => $notif->label,
                'description' => $notif->description,
                'link' => $notif->link,
                'created_at' => $notif->created_at,
                'status' => $notif->status,
                'type' => 'personal',
            ]);
        }

        $notifications = $notifications->sortByDesc('created_at')->values();

        $perPage = 10;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $notifications->slice($offset, $perPage),
            $notifications->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('new.pages.profile.notifikasi', [
            'notifications' => $paginated
        ]);
    }

    public function click(Request $request)
    {
        $user = Auth::guard('member')->user();

        if ($request->type === 'system') {
            Notification::firstOrCreate([
                'peserta_id' => $user->id_peserta,
                'system_notification_id' => $request->id,
            ]);
        } else {
            Notification::where('id', $request->id)
                ->where('peserta_id', $user->id_peserta)
                ->update(['status' => 0]);
        }

        $link = SystemNotification::find($request->id)?->link
            ?? Notification::find($request->id)?->link;

        return redirect($link ?? route('profile.notifikasi'));
    }
}
