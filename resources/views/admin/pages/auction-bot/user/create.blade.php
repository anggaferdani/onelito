@extends('admin.layouts.app')
@section('main')
<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Member</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('bot-users.index') }}">Data Member</a></div>
                    <div class="breadcrumb-item">Tambah User</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h6>Tambah User</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('bot-users.store') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Role</label>
                                        <select name="role" id="role" class="form-control">
                                            <option value="" selected disabled>--Pilih Role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ Str::title($role->name) }}</option>
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
