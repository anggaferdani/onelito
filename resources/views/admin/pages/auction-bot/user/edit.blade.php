@extends('admin.layouts.app')
@section('main')
<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Member</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('bot-users.index') }}">Data Member</a></div>
                    <div class="breadcrumb-item">Edit User</div>
                </div>
            </div>
            <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h6>Edit User</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('bot-users.update', $item->id) }}" method="post">
                                @csrf
                                @method('patch')
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $item->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ $item->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="" selected disabled>--Pilih Role --</option>
                                        @foreach ($roles as $role)
                                            <option @if($role->name === $item->roles->pluck('name')->first()) selected @endif value="{{ $role->name }}">{{ Str::title($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                                <div class="form-group">
                                <button class="btn btn-primary float-right">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </section>
</div>
@endsection

