@extends('admin.layouts.app')
@section('main')
<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Member</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('bot-member.index') }}">Data Member</a></div>
                    <div class="breadcrumb-item">Detail Member</div>
                </div>
            </div>
            <div class="section-body">
            <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6>Detail Member</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Nama Lengkap</th>
                                <td>{{ $item->full_name }}</td>
                            </tr>
                            <tr>
                                <th>No. Hp</th>
                                <td>{{ $item->no_telp }}</td>
                            </tr>
                            <tr>
                                <th>Kota</th>
                                <td>{{ $item->kota_tinggal }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $item->alamat_tinggal }}</td>
                            </tr>
                            <tr>
                                <th>No. Transaksi</th>
                                <td>{{ $item->number_transaction }} </td>
                            </tr>
                            <tr>
                                <th>Aksi</th>
                                <td>
                                    <a href="{{ route('bot-member.edit', $item->user_id) }}"
                                        class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('bot-member.destroy', $item->user_id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
            </div>
    </section>
</div>
@endsection
