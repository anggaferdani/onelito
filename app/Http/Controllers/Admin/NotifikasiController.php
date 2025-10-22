<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request) {
        $query = SystemNotification::where('status', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', '%' . $search . '%');
            });
        }

        $notifications = $query->latest()->paginate(10);

        return view('admin.pages.new.notifikasi.index', compact(
            'notifications',
        ))->with(['type_menu' => 'notifikasi']);
    }

    public function create() {}

    public function store(Request $request) {
        try {
            $request->validate([
                'label' => 'required',
                'description' => 'required',
                'link' => 'nullable',
            ]);

            $array = [
                'label' => $request['label'],
                'description' => $request['description'],
                'link' => $request['link'],
            ];

            SystemNotification::create($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        try {
            $notification = SystemNotification::find($id);
    
            $request->validate([
                'label' => 'required',
                'description' => 'required',
                'link' => 'nullable',
            ]);
    
            $array = [
                'label' => $request['label'],
                'description' => $request['description'],
                'link' => $request['link'],
            ];
    
            $notification->update($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $notification = SystemNotification::find($id);

            $notification->update([
                'status' => 0,
            ]);

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
