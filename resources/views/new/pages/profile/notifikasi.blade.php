@extends('new.templates.profile')
@section('title', 'Notifikasi')
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
@php
  $auth = Auth::guard('member')->user();
@endphp
<div class="">
  <form action="{{ route('profile.notifikasi.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="fs-3 fw-bold">Notifikasi</div>
    <div class="mb-3">Atur notifikasi yang ingin kamu terima disini</div>
    <div class="alert alert-secondary py-1 d-flex justify-content-between mb-3">
      <div class="fw-bold">Transaksi</div>
      <div class="fw-bold">E-mail</div>
    </div>
    <div class="mb-5">
      <div class="fw-bold"><i class="fa-solid fa-cart-shopping"></i> Transaksi Pembelian</div>
      <hr>
      <div class="form-check d-flex justify-content-between p-0">
        <label class="form-check-label" for="flexCheckPembayaran">Menunggu Pembayaran</label>
        <input class="form-check-input" type="checkbox" name="menunggu_pembayaran" value="1" id="flexCheckPembayaran"
          @if($auth->menunggu_pembayaran == 1) checked @endif>
      </div>
      <hr>
      <div class="form-check d-flex justify-content-between p-0">
        <label class="form-check-label" for="flexCheckKonfirmasi">Menunggu Konfirmasi</label>
        <input class="form-check-input" type="checkbox" name="menunggu_konfirmasi" value="1" id="flexCheckKonfirmasi"
          @if($auth->menunggu_konfirmasi == 1) checked @endif>
      </div>
      <hr>
      <div class="form-check d-flex justify-content-between p-0">
        <label class="form-check-label" for="flexCheckDiproses">Pesanan Diproses</label>
        <input class="form-check-input" type="checkbox" name="pesanan_diproses" value="1" id="flexCheckDiproses"
          @if($auth->pesanan_diproses == 1) checked @endif>
      </div>
      <hr>
      <div class="form-check d-flex justify-content-between p-0">
        <label class="form-check-label" for="flexCheckDikirim">Pesanan Dikirim</label>
        <input class="form-check-input" type="checkbox" name="pesanan_dikirim" value="1" id="flexCheckDikirim"
          @if($auth->pesanan_dikirim == 1) checked @endif>
      </div>
      <hr>
      <div class="form-check d-flex justify-content-between p-0">
        <label class="form-check-label" for="flexCheckSelesai">Pesanan Selesai</label>
        <input class="form-check-input" type="checkbox" name="pesanan_selesai" value="1" id="flexCheckSelesai"
          @if($auth->pesanan_selesai == 1) checked @endif>
      </div>
      <hr>
      <div class="form-check d-flex justify-content-between p-0">
        <label class="form-check-label" for="flexCheckPengingat">Pengingat</label>
        <input class="form-check-input" type="checkbox" name="pengingat" value="1" id="flexCheckPengingat"
          @if($auth->pengingat == 1) checked @endif>
      </div>
    </div>
    <button type="submit" class="btn btn-danger">Update</button>
  </form>
</div>
@endsection