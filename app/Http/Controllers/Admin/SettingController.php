<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index(Request $request) {
        $query = Setting::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $settings = $query->latest()->paginate(10);

        return view('admin.pages.new.setting.index', compact(
            'settings',
        ))->with(['type_menu' => 'settings']);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        try {
            $setting = Setting::find($id);
    
            $request->validate([
                'title' => 'required',
                'description' => 'required',
            ]);
    
            $array = [
                'title' => $request['title'],
                'description' => $request['description'],
            ];

            $setting->update($array);

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {}
}
