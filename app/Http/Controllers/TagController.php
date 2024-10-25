<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request) {
        $query = Tag::where('status', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tag', 'like', '%' . $search . '%');
            });
        }

        $tags = $query->paginate(10);

        return view('admin.pages.tag.index', compact(
            'tags',
        ))->with(['type_menu' => 'tag']);
    }

    public function create() {}

    public function store(Request $request) {
        try {
            $request->validate([
                'tag' => 'required',
            ]);

            $array = [
                'tag' => $request['tag'],
            ];

            Tag::create($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        try {
            $tag = Tag::find($id);
    
            $request->validate([
                'tag' => 'required',
            ]);
    
            $array = [
                'tag' => $request['tag'],
            ];
    
            $tag->update($array);
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $tag = Tag::find($id);

            $tag->update([
                'status' => 0,
            ]);

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
