@extends('new.templates.pages')
@section('title', 'Winning Auction')
@section('content')
<div class="my-3">
  @foreach($carts as $cart)
    @php
        $cartPhoto = url('img/uniring.jpeg');
        $cartable = $cart->cartable;

        if ($cart->cartable_type === 'EventFish') {
            $cartPhoto = url('img/koi11.jpg');
        }

        if ($cart->cartable->photo !== null) {
            $cartPhoto = url('storage') . '/' . $cart->cartable->photo->path_foto;
        }

        if ($cart->cartable_type === 'Product') {
            $cartPrice = $cartable->harga;
        }
    @endphp
    <div class="row g-2">
      <div class="col-3 col-md-2"><img src="{{ $cartPhoto }}" alt="" class="img-fluid border p-2 rounded"></div>
      <div class="col-9 col-md-10">
        <div>
          <p class="m-0">{!! Illuminate\Support\Str::limit("$cartable->variety | $cartable->breeder | $cartable->bloodline | $cartable->size") !!}</p>
          <p class="m-0 d-none"><b>Rp. {{ number_format($cart->price, 0, '.', '.') }}</b></p>
        </div>
      </div>
    </div>
    <hr>
  @endforeach
</div>
@endsection
