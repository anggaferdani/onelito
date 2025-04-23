<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index(Request $request) {
        $query = Label::where('status', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', '%' . $search . '%');
            });
        }

        $labels = $query->latest()->paginate(10);

        return view('admin.pages.new.label.index', compact(
            'labels',
        ))->with(['type_menu' => 'label']);
    }

    public function create() {}

    public function store(Request $request) {
        try {
            $request->validate([
                'label' => 'required',
            ]);

            $array = [
                'label' => $request['label'],
            ];

            Label::create($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        try {
            $label = Label::find($id);
    
            $request->validate([
                'label' => 'required',
            ]);
    
            $array = [
                'label' => $request['label'],
            ];
    
            $label->update($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $label = Label::find($id);

            $label->update([
                'status' => 0,
            ]);

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
