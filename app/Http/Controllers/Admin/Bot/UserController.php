<?php

namespace App\Http\Controllers\Admin\Bot;

use App\Http\Controllers\Controller;
use App\Models\Bot\Role;
use App\Models\Bot\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = User::orderBy('name','ASC')->get();
        return view('admin.pages.auction-bot.user.index',[
            'title' => 'Data User',
            'type_menu' => 'bot-user',
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereNotIn('name',['member'])->orderBy('name','ASC')->get();

        return view('admin.pages.auction-bot.user.create',[
            'title' => 'Tambah User',
            'type_menu' => 'bot-user',
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required'],
            'role' => ['required']
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt('password')
        ]);

        $user->assignRole(request('role'));

        return redirect()->route('bot-users.index')->with('success','User berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::orderBy('name','ASC')->get();
        $item = User::findOrFail($id);

        return view('admin.pages.auction-bot.user.edit',[
            'title' => 'Edit User',
            'roles' => $roles,
            'type_menu' => 'bot-user',
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required','email',Rule::unique('users','email')->ignore($id)],
            'role' => ['required']
        ]);

        $user = User::findOrFail($id);
        if(request('password')){
            $password = bcrypt(request('password'));
        }else{
            $password = $user->password;
        }
        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => $password
        ]);

        $user->syncRoles(request('role'));

        return redirect()->route('bot-users.index')->with('success','User berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('bot-users.index')->with('success','User berhasil dihapus');
    }
}
