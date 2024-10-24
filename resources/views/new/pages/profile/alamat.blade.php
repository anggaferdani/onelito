@extends('new.templates.profile')
@section('title', 'Alamat')
@section('content')
<div class="row align-items-center g-2 mb-3">
  <div class="col-md-6">
    <form id="searchForm" action="{{ route('alamat.index') }}" method="GET">
      <input type="text" id="searchInput" name="search" class="form-control rounded" placeholder="Tulis label / nama / alamat penerima" value="{{ request('search') }}">
    </form>
  </div>
  <div class="col-md-4 ms-auto"><button type="" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Alamat Baru</button></div>
</div>
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
@php
  $auth = Auth::guard('member')->user();
@endphp
<div class="row g-2">
  @foreach($alamats as $alamat)
    <div class="col-12">
      <div class="card @if($auth->pilih_alamat == $alamat->id) border-danger alert-danger @endif">
        <div class="card-body p-4">
          <div class="row g-3 align-items-center">
            <div class="col-md-10">
              <div class="fw-bold small mb-1">{{ $alamat->label }} @if($auth->alamat_utama == $alamat->id) <span class="badge bg-secondary">Utama</span> @endif</div>
              <div class="fw-bold mb-1">{{ $alamat->nama_penerima }}</div>
              <div class="mb-1">{{ $alamat->no_hp }}</div>
              <div class="mb-1">{{ $alamat->alamat_lengkap }}</div>
              <div class="d-md-flex align-items-center d-block gap-2">
                <div class="text-danger small" data-bs-toggle="modal" data-bs-target="#editModal{{ $alamat->id }}">Ubah Alamat</div>
                <div class="text-danger small d-none d-md-flex">|</div>
                @if($auth->alamat_utama != $alamat->id)
                  <a href="{{ route('alamat.alamat-utama', ['alamatId' => $alamat->id]) }}" class="text-danger small text-decoration-none">Jadikan Alamat Utama</a>
                  <div class="text-danger small d-none d-md-flex">|</div>
                @endif
                <form action="{{ route('alamat.destroy', $alamat->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="border-0 bg-transparent text-danger small delete p-0">Hapus</button>
                </form>
              </div>
            </div>
            <div class="col-md-2 @if($auth->pilih_alamat == $alamat->id) d-none d-md-flex align-items-center @endif">
              @if($auth->pilih_alamat == $alamat->id)
                <div class="m-auto"><i class="fa-solid fa-check text-danger"></i></div>
              @else
                <a href="{{ route('alamat.pilih-alamat', ['alamatId' => $alamat->id]) }}" class="btn btn-danger d-none d-md-block">Pilih</a>
                <a href="{{ route('alamat.pilih-alamat', ['alamatId' => $alamat->id]) }}" class="btn btn-danger w-100 d-block d-md-none">Pilih</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <div class="d-flex justify-content-end">{{ $alamats->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
</div>

<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Create</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('alamat.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="label" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_penerima" required>
          </div>
          <div class="mb-3">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="no_hp" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" name="alamat_lengkap" required></textarea>
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

@foreach($alamats as $alamat)
<div class="modal fade" id="editModal{{ $alamat->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('alamat.update', $alamat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="label" required value="{{ $alamat->label }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_penerima" required value="{{ $alamat->nama_penerima }}">
          </div>
          <div class="mb-3">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="no_hp" required value="{{ $alamat->no_hp }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" name="alamat_lengkap" required>{{ $alamat->alamat_lengkap }}</textarea>
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
@endforeach
@endsection
@push('scripts')
  <script>
    document.getElementById('searchInput').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
  </script>
@endpush