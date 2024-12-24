<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request) {
        $query = Order::where('status_aktif', 1);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.pages.new.pesanan.index', compact(
            'orders',
        ))->with(['type_menu' => 'order']);
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

    public function detail($no_order) {
        try {
            $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

            if ($order) {
                return view('admin.pages.new.pesanan.detail', compact(
                    'order',
                ))->with(['type_menu' => 'order']);
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
