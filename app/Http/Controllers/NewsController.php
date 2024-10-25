<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function news() {
        $news = News::where('status', 1)->latest()->paginate(9);
        return view('new.pages.news.news', compact(
            'news',
        ));
    }

    public function detail($lug) {
        $new = News::where('slug', $lug)->first();

        return view('new.pages.news.detail', compact(
            'new',
        ));
    }

    public function index(Request $request) {
        $query = News::where('status', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $news = $query->paginate(10);
        $tags = Tag::where('status', 1)->get();

        return view('admin.pages.news.index', compact(
            'news',
            'tags',
        ))->with(['type_menu' => 'news']);
    }

    public function create() {}

    public function store(Request $request) {
        try {
            $request->validate([
                'image' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);

            $slug = $this->generateSlug($request->input('title'));
    
            $array = [
                'slug' => $slug,
                'image' => $this->handleFileUpload($request->file('image'), 'storage/news/'),
                'title' => $request['title'],
                'description' => $request['description'],
            ];

            $new = News::create($array);

            $new->tags()->sync($request->input('tags'));
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {
        try {
            $new = News::find($id);
    
            $request->validate([
                'title' => 'required',
                'description' => 'required',
            ]);
    
            $array = [
                'title' => $request['title'],
                'description' => $request['description'],
            ];

            if ($request->hasFile('image')) {
                $array['image'] = $this->handleFileUpload($request->file('image'), 'storage/news/');
            }

            if ($new->title !== $request->input('title')) {
                $array['slug'] = $this->generateSlug($request->input('title'));
            }
    
            $new->update($array);

            $new->tags()->sync($request->input('tags'));
    
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $tag = News::find($id);

            $tag->update([
                'status' => 0,
            ]);

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    private function handleFileUpload($file, $path)
    {
        if ($file) {
            $fileName = date('YmdHis') . rand(999999999, 9999999999) . $file->getClientOriginalExtension();
            $file->move(public_path($path), $fileName);
            return $fileName;
        }
        return null;
    }

    private function generateSlug($title) {
        $slug = Str::slug($title);
        $count = News::where('slug', 'like', "$slug%")->count();
    
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
    
        return $slug;
    }
}
