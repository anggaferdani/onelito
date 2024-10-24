@extends('new.templates.profile')
@section('title', 'Profile')
@section('content')
@if(Session::get('success'))
  <div class="alert alert-important alert-success" role="alert">
    {{ Session::get('success') }}
  </div>
@endif
@if(Session::get('error'))
  <div class="alert alert-important alert-danger" role="alert">
    {{ Session::get('error') }}
  </div>
@endif
<div class="row g-3">
  <div class="col-md-4">
    <div class="mb-3">
      <img src="{{ $auth->profile_pic ? asset('storage/' . $auth->profile_pic) : asset('/img/default.png') }}" alt="" class="img-fluid w-100">
    </div>
    <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#editProfile">Edit Profile</button>
  </div>
  <div class="col-md-8">
    <div>Nama :</div>
    <div class="fw-bold mb-2">{{ $auth->nama }}</div>
    <div>Email :</div>
    <div class="fw-bold mb-2">{{ $auth->email }}</div>
    <div>No HP :</div>
    <div class="fw-bold mb-2">{{ $auth->no_hp }}</div>
  </div>
</div>

<div class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('profile.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" name="profile_pic" value="{{ $auth->profile_pic }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Depan</label>
            <input type="text" class="form-control" name="nama_depan" value="{{ $auth->nama_depan }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Belakang</label>
            <input type="text" class="form-control" name="nama_belakang" value="{{ $auth->nama_belakang }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $auth->email }}">
          </div>
          <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="number" class="form-control" name="no_hp" value="{{ $auth->no_hp }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection