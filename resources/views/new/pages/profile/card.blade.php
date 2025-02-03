@extends('new.templates.profile')
@section('title', 'Card')
@section('content')
@php
  use Milon\Barcode\DNS2D;
  $auth = Auth::guard('member')->user();
@endphp
@if($auth->kode_member == null)
<div>
  <div class="mb-3">Anda belum memiliki member card. Dengan member card, Anda bisa mendapatkan berbagai keuntungan menarik, termasuk akses prioritas ke promo terbaru, harga spesial, dan informasi eksklusif tentang layanan kami.</div>
  <div class="text-center">
    <a href="{{ route('profile.member') }}" class="btn btn-danger">Buat member card</a>
  </div>
</div>
@else
@php
  $dns2D = new DNS2D();
  $qrCode = $dns2D->getBarcodePNG((string) $auth->kode_member, 'QRCODE');
@endphp
<div class="m-auto rounded d-flex justify-content-between flex-column p-3" style="width: 300px; height: 170px; background-position: center; background-size: cover; background: url({{ asset('img/card.png') }});">
  <div><img src="{{ asset('img/logo-bawah.png') }}" alt="" width="25"></div>
  <div class="d-flex align-items-center justify-content-between w-100">
    <div class="">
      <div style="font-size: 11px;" class="text-white fw-bold">{{ $auth->kode_member }}</div>
      <div style="font-size: 11px;" class="text-white">{{ $auth->nama }}</div>
    </div>
    <div class="">
      <div class="bg-white p-1 rounded"><img src="data:image/png;base64, {{ $qrCode }}" alt="" style="width: 70px; height: 70px;" /></div>
    </div>
  </div>
  <div class="text-white" style="font-size: 11px;">Member <span class="fw-bold">Card</span></div>
</div>
@endif
@endsection