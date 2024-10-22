@extends('profil')

@section('container')
    <div class="container my-3 text-center">
        <h5><i class="fa-solid fa-bag-shopping"></i> <b>Purchase history</b></h5>
    </div>
    <div class="row">

    @forelse($orders as $order)
            @php

                $orderPhoto = url('img/bio_media.png');
                $productable = $order->productable;

                if ($order->productable_type === 'EventFish') {
                    $wishlistPhoto = url('img/koi11.jpg');
                }

                if ($productable->photo !== null) {
                    $orderPhoto = url('storage') . '/' . $productable->photo->path_foto;
                }
            @endphp

        @if($order->productable_type === 'EventFish')
        <div class="col-6 mt-2">
            <div class="card p-1">
                <!-- <a href="/detail_onelito_store"> -->
                    <img src="{{ $orderPhoto }}" alt=""
                        class="card-img-top purchase-item w-100">
                <!-- </a> -->
                <p>{!! Illuminate\Support\Str::limit(
                                        "$productable->variety | $productable->breeder | $productable->size | $productable->bloodline",
                                        25,
                                    ) !!}</p>
                <p><b>Rp.{{ number_format($order->total, 0, '.', '.') }}</b></p>
            </div>
        </div>
        @endif

        @if($order->productable_type === 'Product')
        <div class="col-6 mt-2">
            <div class="card p-1">
                <!-- <a href="/detail_onelito_store"> -->
                <img src="{{ $orderPhoto }}" alt="bio media"
                        class="card-img-top purchase-item w-100">
                <!-- </a> -->
                <p>{!! Illuminate\Support\Str::limit(
                                    $productable->merek_produk . ' ' . $productable->nama_produk,
                                    25,
                                ) !!}
                                </p>
                <p><b>Rp.{{ number_format($order->total, 0, '.', '.') }}</b></p>
            </div>
        </div>
        @endif
    @empty
    @endforelse
            <!-- <div class="col-6 mt-2">
                <div class="card">
                    <a href="/detail_onelito_store">
                        <img src="img/bio_media.png" alt="bio media" class="card-img-top">
                    </a>
                    <div class="card-body">
                        <p>Bio Tube Bacteria House Media Filter</p>
                        <p><b>Rp. 1.300.000</b></p>
                    </div>
                </div>
            </div> -->
        </div>
    @endsection
