@extends('profil')

@section('container')
    <div class="container my-3 text-center d-none">
        <h5><i class="fa-solid fa-cart-shopping"></i> <b>Shopping cart</b></h5>
    </div>
    <div class="container">
        <div class="container border-top border-bottom py-3">
            <input class="form-check-input my-auto cart-check-mobile-all checkbox-mobile" style="font-size:large" type="checkbox" value="" id="Pilih Semua">
            <label class="form-check-label" for="Pilih Semua">
                Pilih Semua
            </label>
        </div>

        @forelse($storeCarts as $cart)
            @php

                $cartPhoto = url('img/uniring.jpeg');
                $cartable = $cart->cartable;

                if ($cart->cartable_type === 'KoiStock') {
                    $cartPhoto = url('');
                    $cartPrice = $cartable->harga_ikan;
                                            
                    if ($cartable->foto_ikan !== null) {
                        $cartPhoto = url('storage') . '/' . $cart->cartable->foto_ikan;
                    }
                }

                if ($cart->cartable->photo !== null) {
                    $cartPhoto = url('storage') . '/' . $cart->cartable->photo->path_foto;
                }

                if ($cart->cartable_type === 'Product') {
                    $cartPrice = $cartable->harga;
                }
            @endphp
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
                    <div class="container d-flex p-0 my-3 justify-content-end">
                        <button class="border-0 mr-3 remove-cart"
                                data-id="{{ $cart->id_keranjang }}"
                                style="background-color: transparent"><i
                                class="fa-regular fa-trash-can"></i></button>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button"
                            onclick="manageProduct(this)"
                            class="border-0 btn-light mr-2" style="background-color:tranparent">
                                <i class="fa-sharp fa-solid fa-circle-minus text-black-50" style="font-size: larger"></i>
                            </button>
                            <h1
                            data-id="{{ $cart->id_keranjang }}"
                            class="outputproduct outputproduct-{{ $cart->id_keranjang }}"> {{ $cart->jumlah }} </h1>
                            <button type="button"
                            id="add"
                            onclick="manageProduct(this)"
                            class=" border-0 btn-light ml-2">
                                <i class="fa-solid fa-circle-plus text-danger" style="font-size: larger"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            @endif
            @if ($cart->cartable_type === 'KoiStock')
                <div class="container">
                    <div class="container d-flex p-0 my-3">
                        <input class="form-check-input mr-3 my-auto cart-check-mobile checkbox-mobile"
                            type="checkbox" data-price="{{ $cartPrice }}"
                            data-id="{{ $cart->id_keranjang }}"
                            data-cartableid="{{ $cart->cartable_id }}"
                            data-type="koistock" value=""
                            id="flexCheckDefault">
                        <div class="card mr-3">
                            <a href="/koi_stok/{{ $cart->cartable_id }}"><img
                                    src="{{ $cartPhoto }}"
                                    class="card-img-top"
                                    style="width: 96px; height: 144px; object-fit: cover;"
                                    alt="..."></a>
                        </div>
                        <div>
                            <p class="m-0">{!! Illuminate\Support\Str::limit(
                                "$cartable->variety | $cartable->breeder | $cartable->size | $cartable->bloodline",
                                75,
                            ) !!}</p>
                            <p class="m-0"><b>Rp.
                                    {{ number_format($cartPrice, 0, '.', '.') }}</b>
                            </p>
                        </div>
                    </div>
                    <div class="container d-flex p-0 my-3 justify-content-end">
                        <!-- <p class="my-auto text-danger">Tulis Catatan</p> -->
                        <p class="my-auto text-center d-none">
                            Pindahkan ke Wishlist |
                        </p>
                        <button class="border-0 mr-3 remove-cart"
                            data-id="{{ $cart->id_keranjang }}"
                            style="background-color: transparent"><i
                                class="fa-regular fa-trash-can"></i></button>
                        <div class="btn-group d-none" role="group"
                            aria-label="Basic outlined example">
                            <button type="button" id="subtract"
                                onclick="manageProduct(this)"
                                class="border-0 btn-light mr-2"
                                style="background-color:tranparent">
                                <i class="fa-sharp fa-solid fa-circle-minus text-black-50"
                                    style="font-size: larger"></i>
                            </button>
                            <button type="button" id="output"
                                data-id="{{ $cart->id_keranjang }}"
                                class="btn btn-light outputproduct outputproduct-{{ $cart->id_keranjang }}">{{ $cart->jumlah }}</button>
                            <button id="add" type="button"
                                onclick="manageProduct(this)"
                                class=" border-0 btn-light ml-2">
                                <i class="fa-solid fa-circle-plus text-danger"
                                    style="font-size: larger"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr class="float-sm-end text-center mb-3" style="width: 98%;">
            @endif
        @empty
        @endforelse
        <div class="container border-top fixed-bottom p-3 bg-white">
            <div class="d-flex justify-content-between">
                <div class="my-auto">
                    <h5 class="">Total Harga</h5>
                    <h5 class="font-bold pricetotal">Rp. <span class="total-price">0</span></h5>
                </div>
                <a onclick=""
            class="btn btn-danger w-50"
            style="padding:4%;"
            href="{{ url('/onelito_store').'?item='. request()->item }}">Belanja Produk Lainya</a>
                <a class="transaction btn btn-secondary w-25" onclick="itemCheckNotif()" href="#" role="button">Pesan
                    Sekarang</a>
            </div>
                
        </div>
    </div>
@endsection
