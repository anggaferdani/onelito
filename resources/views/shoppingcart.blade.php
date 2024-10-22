@extends('profil')

@section('container')
    <div class="container my-3 text-center d-none">
        <h5><i class="fa-solid fa-cart-shopping"></i> <b>Shopping cart</b></h5>
    </div>
    <div class="container">
        <div class="d-none container border-top border-bottom py-3">
            <input class="form-check-input my-auto cart-check-mobile-all checkbox-mobile" style="font-size:large" type="checkbox" value="" id="Pilih Semua">
            <label class="form-check-label" for="Pilih Semua">
                Pilih Semua
            </label>
        </div>

        @forelse($carts as $cart)
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
            @if($cart->cartable_type === 'EventFish')
                <div class="container">
                    <div class="container d-flex p-0 my-3">
                        <input class="d-none form-check-input mr-3 my-auto cart-check-mobile checkbox-mobile" type="checkbox" value=""
                        data-price="{{ $cart->price }}"
                        data-id="{{ $cart->id_keranjang }}"
                        data-cartableid="{{ $cart->cartable_id }}"
                        data-type="eventfish" value=""
                        id="flexCheckDefault">
                        <div class="card mr-3">
                            <a href="/auction/{{$cart->cartable_id}}"><img src="{{ $cartPhoto }}" class="card-img-top"
                                    style="height: 10vh; width: 25vw; object-fit: cover;" alt="..."></a>
                        </div>
                        <div>
                        <p class="m-0">{!! Illuminate\Support\Str::limit(
                                        "$cartable->variety | $cartable->breeder | $cartable->bloodline | $cartable->size",
                                        64,
                                    ) !!}</p>
                        <p class="m-0 d-none"><b>Rp. {{ number_format($cart->price, 0, '.', '.') }}</b></p>
                        </div>
                    </div>
                    <div class="container d-flex p-0 my-3 justify-content-between">
                        <!-- <p class="my-auto text-danger">Tulis Catatan</p> -->
                    </div>
                </div>
                <hr>
            @endif
            @if($cart->cartable_type === 'Product')
                <div class="container">
                    <div class="container d-flex p-0 my-3">
                        <input class="form-check-input mr-3 my-auto cart-check-mobile checkbox-mobile" type="checkbox" value=""
                        data-price="{{ $cartPrice }}"
                        data-id="{{ $cart->id_keranjang }}"
                        data-cartableid="{{ $cart->cartable_id }}"
                        data-type="product"
                        id="flexCheckDefault">
                        <div class="card mr-3">
                            <a href="/detail_onelito_store"><img src="{{ $cartPhoto }}" class="card-img-top"
                                    style="height: 10vh; width: 25vw; object-fit: cover;" alt="..."></a>
                        </div>
                        <div>
                        <p class="m-0">{{ $cartable->nama_produk}}</p>
                        <p class="m-0"><b>Rp. {{ number_format($cartPrice, 0, '.', '.') }}</b></p>
                        </div>
                    </div>
                    <div class="container d-flex p-0 my-3 justify-content-between">
                        <p class="my-auto text-danger">Tulis Catatan</p>
                        <p class="my-auto text-center">
                            Pindahkan ke Wishlist |
                        </p>
                        <button class="border-0" style="background-color: transparent"><i
                                class="fa-regular fa-trash-can"></i></button>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="border-0 btn-light mr-2" style="background-color:tranparent">
                                <i class="fa-sharp fa-solid fa-circle-minus text-black-50" style="font-size: larger"></i>
                            </button>
                            <h1> {{ $cart->jumlah }} </h1>
                            <button type="button" class=" border-0 btn-light ml-2">
                                <i class="fa-solid fa-circle-plus text-danger" style="font-size: larger"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            @endif
        @empty
        @endforelse
        <div class="container border-top fixed-bottom d-flex p-3 justify-content-between bg-white d-none">
            <div class="my-auto">
                <h5 class="">Total Harga</h5>
                <h5 class="font-bold pricetotal">Rp. <span class="total-price">0</span></h5>
            </div>
            {{-- <a class="transaction btn btn-secondary w-25" onclick="orderNowProcess()" href="#" role="button">Pesan
                Sekarang</a> --}}
        </div>
    </div>
@endsection
