@extends('admin.layouts.app')
@section('main')
<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Member</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('bot-member.index') }}">Data Member</a></div>
                    <div class="breadcrumb-item">Edit Member</div>
                </div>
            </div>
            <div class="section-body">
            <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6>Edit Member</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('bot-member.update', $item->user_id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="full_name">Nama Lengkap</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                    name="full_name" id="full_name" value="{{ $item->full_name }}">
                                @error('full_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- <div class="form-group">
                                <label for="kota_tinggal">Provinsi Tempat Tinggal</label>
                                <input type="text"
                                    class="form-control @error('kota_tinggal') is-invalid @enderror"
                                    name="kota_tinggal" id="kota_tinggal"
                                    value="{{ $item->kota_tinggal }}">
                                @error('kota_tinggal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div> --}}
                            <div class="form-group">
                                <label for="kota_tinggal">Kota Tempat Tinggal</label>
                                <input type="text" class="form-control @error('kota_tinggal') is-invalid @enderror"
                                    name="kota_tinggal" id="kota_tinggal" value="{{ $item->kota_tinggal }}">
                                @error('kota_tinggal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="alamat_tinggal">Alamat Tinggal</label>
                                <textarea type="text" class="form-control" name="alamat_tinggal" id="alamat_tinggal" cols="30"
                                    rows="5">{{ $item->alamat_tinggal }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="no_telp">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                                    name="no_telp" id="no_telp" value="{{ $item->no_telp }}">
                                @error('no_telp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="number_transaction">No. Transaksi</label>
                                <input type="number" class="form-control @error('number_transaction') is-invalid @enderror"
                                    name="number_transaction" id="number_transaction"
                                    value="{{ $item->number_transaction }}">
                                @error('number_transaction')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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

