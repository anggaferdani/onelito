@extends('new.templates.pages')
@section('title', 'Success')
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
<div class="row py-5">
  <div class="col-md-6 mx-auto">
    <div class="d-flex mb-3"><img src="{{ asset('img/frame_reg.png') }}" class="mx-auto rounded-circle border border-success border-3" alt="" width="100"></div>
    <div class="mb-2 text-center fw-bold fs-3">Thank You</div>
    <div class="mb-3 text-center">You order <span class="fw-bold">{{ $order->no_order }}</span> has been paid for successfully</div>
    <table class="w-100 mb-3">
      <tbody>
        <tr>
          <td style="width: 30%;" class="fw-bold">Amount Paid</td>
          <td>:</td>
          <td>{{ 'Rp. ' . number_format($order->total_tagihan, 0, '.', '.') }} @if($order->coin_yang_digunakan > 0) <span class="text-success small">(Potongan Onelito Coins {{ 'Rp. ' . number_format($order->coin_yang_digunakan, 0, '.', '.') }})</span> @endif</td>
        </tr>
        <tr>
          <td style="width: 30%;" class="fw-bold">Date Paid</td>
          <td>:</td>
          <td>{{ \Carbon\Carbon::parse($order->paid_at)->translatedFormat('d F Y, H:i') }}</td>
        </tr>
        <tr>
          <td style="width: 30%;" class="fw-bold">Payment</td>
          <td>:</td>
          <td>{{ $order->payment_channel }}</td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex">
      <a href="{{ route('shopping-cart.menunggu-konfirmasi') }}" class="btn btn-primary text-center mx-auto">Next</a>
    </div>
  </div>
</div>
@endsection