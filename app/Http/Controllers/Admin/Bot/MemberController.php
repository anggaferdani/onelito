<?php

namespace App\Http\Controllers\Admin\Bot;

use App\Http\Controllers\Controller;
use App\Models\Bot\Member;
use App\Models\Bot\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index()
    {
        $items = Member::latest()->get();
        return view('admin.pages.auction-bot.member.index',[
            'title' => 'Data Member',
            'type_menu' => 'bot-member',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('admin.pages.auction-bot.member.create',[
            'title' => 'Tambah Member',
            'type_menu' => 'bot-member',
        ]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'full_name' => ['required'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required'],
            'tanggal_lahir' => ['required']
        ]);

        $member = User::create([
            'name' => request('full_name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        $member->assignRole('member');
        $data = request()->all();
        $data['upload_time'] = Carbon::now();
        $member->member()->create($data);

        return redirect()->route('bot-member.index')->with('success','Member berhasil disimpan');
    }

    public function show($id)
    {
        $item = Member::findOrFail($id);
        return view('admin.pages.auction-bot.member.show',[
            'title' => 'Detail Member',
            'item' => $item,
            'type_menu' => 'bot-member',
        ]);
    }

    public function edit($id)
    {
        $item = Member::findOrFail($id);
        return view('admin.pages.auction-bot.member.edit',[
            'title' => 'Edit Member',
            'item' => $item,
            'type_menu' => 'bot-member',
        ]);
    }

    public function update($id)
    {
        $member = Member::findOrFail($id);
        request()->validate([
            'full_name' => ['required'],
        ]);
        $data = request()->all();
        $member->update($data);

        $member->user()->update([
            'name' => request('full_name'),
        ]);

        return redirect()->route('bot-member.index')->with('success','Member berhasil disimpan');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return redirect()->route('bot-member.index')->with('success','Member berhasil dihapus');
    }
}
