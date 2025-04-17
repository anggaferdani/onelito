@extends('layout.mainlog')

@section('container')
    <style>
        .card-img-top.product {
            min-height: 367px;
            max-width: 245px;
            object-fit: contain;
            border: 1px solid #dee2e6!important
        }

        .card-img-top {
            min-height: 367px;
        }

        .card-img-top {
            max-height: 367px;
        }

        @media screen and (min-width: 601px) {
            .addcart.mobile {
                display: none
            }
        }

        /* On screens that are 600px or less, set the background color to olive */
        @media screen and (max-width: 600px) {
            .addcart.desktop {
                display: none;
            }

            .card-img-top.product {
                min-height: 254px;
                object-fit: contain;
                border: 1px solid #dee2e6!important
            }

            .card-img-top {
                min-height: 254px;
            }
        }

        @media screen and (min-width: 601px) and (max-width: 1332px) {
         

            .card-img-top.product {
                min-height: 364px;
                object-fit: contain;
                border: 1px solid #dee2e6!important
            }

            .addcart.desktop {
                display: block
            }

            .addcart.mobile {
                display: none;
            }
        }
    </style>
    <br><br><br><br><br>

    <div class="container-fluit">
        <div class="container">
            <h4 class="m-1">{{ count($wishlists) ?? '' }} <span>Barang</span></h4>
            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3 mb-5">
                @forelse($wishlists as $wishlist)
                    @php

                        $wishlistPhoto = url('img/produk1.jpeg');
                        $wishlistable = $wishlist->wishlistable;

                        if ($wishlist->wishlistable_type === 'EventFish') {
                            $wishlistPhoto = url('img/koi11.jpg');
                            $currentMaxBid = $wishlistable->ob;

                            if ($wishlistable->maxBid !== null) {
                                $currentMaxBid = $wishlistable->maxBid->nominal_bid;
                            }
                        }

                        if ($wishlist->wishlistable->photo !== null) {
                            $wishlistPhoto = url('storage') . '/' . $wishlist->wishlistable->photo->path_foto;
                        }

                        if ($wishlist->wishlistable_type === 'KoiStock') {
                            $wishlistPhoto = url('img/koi12.jpg');

                            if ($wishlistable->foto_ikan !== null) {
                                $wishlistPhoto = url('storage') . '/' . $wishlistable->foto_ikan;
                            }
                        }
                    @endphp

                    @if ($wishlist->wishlistable_type === 'EventFish')
                        <div class="col">
                            <div class="border">
                                <a href="{{ '/auction-bid-now/' . $wishlistable->id_ikan }}"><img src="{{ $wishlistPhoto }}"
                                        alt="bio media" class="img-fluid"></a>
                                <div class="px-1">
                                    <p class="cb-judul">{!! Illuminate\Support\Str::limit(
                                        "$wishlistable->variety | $wishlistable->breeder | $wishlistable->size | $wishlistable->bloodline",
                                        45,
                                    ) !!}
                                    </p>
                                    <div class="row">
                                        <div class="col-9">
                                            <p class="m-0">Number of bids</p>
                                        </div>
                                        <div class="col-3 text-end">
                                            <i>
                                                <i
                                                data-id="{{ $wishlistable->id_ikan }}"
                                                data-type="id_ikan_lelang"
                                                style="color:red;font-size:smaller" class="remove fa-solid fa-trash-can"></i>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="" style="color: red">{{ $wishlistable->bid_details_count }}</p>
                                        </div>

                                        <div class="col-6 ps-lg-3">
                                            @if ($auth !== null)
                                                @if ($wishlistable->maxBid !== null)
                                                    @if ($wishlistable->maxBid->id_peserta === $auth->id_peserta)
                                                        <div class="row">
                                                            <div class="col-2 p-0 px-1 text-end">
                                                                <i style="color:red" class="fa-solid fa-caret-down"></i>
                                                            </div>
                                                            <div class="col-10 p-0 pt-1">
                                                                <p class="m-0" style="font-size:70%;color:red">HIGHEST
                                                                    BID</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row p-2">
                                        <div class="col-6 p-0 ps-1">
                                            <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                            <p class="m-0" style="color: red;font-size:75%">Rp
                                                {{ number_format($currentMaxBid, 0, '.', '.') }}</p>
                                        </div>
                                        <div class="col-6 p-0 pe-1">
                                            <p class="m-0 countdown-title-{{ $wishlistable->id_ikan }}"
                                                style="text-align: end;font-size:80%">Remaining Time</p>
                                            <p class="m-0 countdown-label" data-id="{{ $wishlistable->id_ikan }}"
                                                id="atas-{{ $wishlistable->id_ikan }}"
                                                data-endtime="{{ $wishlistable->event->tgl_akhir }}"
                                                data-end-extratime="{{ $wishlistable->tgl_akhir_extra_time }}"
                                                style="text-align: end;color :red;font-size:75%;">00:00:00</p>
                                        </div>
                                    </div>
                                    <!-- <p style="font-size: 10px" class="card-text ma">Harga
                                                saat ini</p>
                                        <p style="color :red;font-size: 12px" class="m-0">
                                                Rp.
                                                {{ number_format($currentMaxBid, 0, '.', '.') }}
                                            </p> -->
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($wishlist->wishlistable_type === 'Product')
                        <div class="col">
                            <div class="border card">
                                <a href="{{ url('/onelito_store') . '/' . $wishlist->wishlistable_id }}"><img
                                        src="{{ $wishlistPhoto }}" alt="bio media" class="img-fluid product"></a>
                                <div class="card-body px-2">
                                    <p class="">{!! Illuminate\Support\Str::limit(
                                        $wishlist->wishlistable->merek_produk . ' ' . $wishlist->wishlistable->nama_produk,
                                        100,
                                    ) !!}</p>
                                    
                                    
                                    <div class="row" style="height:58px;">
                                        <div class="col-9">
                                        <p class="my-2"><b>Rp.
                                            {{ number_format($wishlist->wishlistable->harga, 0, '.', '.') }}</b></p>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                        <!-- <button class="rounded addcart mobile"
                                                            data-id="{{ $wishlist->wishlistable_id }}"
                                                            style="background-color: red;border-color:red; outline: none; border: none;"><i
                                                                class="fa-solid fa-cart-shopping"
                                                                style="color: white"></i></button> -->
                                            <button class="text-danger addcart" data-id="{{ $wishlist->wishlistable_id }}"
                                                style="background-color: transparent;font-size:small;border-color:red"><i
                                                    class="fa-solid fa-plus"></i>
                                                <span>Keranjang</span>
                                                
                                            </button>
                                        </div>
                                        <div class="col-4 text-end">
                                                <i
                                                data-id="{{ $wishlist->wishlistable_id }}"
                                                data-type="id_produk"
                                                style="color:red;font-size:smaller" class="remove my-2 fa-solid fa-trash-can"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($wishlist->wishlistable_type === 'KoiStock')
                        <div class="col">
                            <div class="card">
                                <a href="{{ url('/koi_stok') . '/' . $wishlist->wishlistable_id }}">
                                    <img
                                    src="{{ $wishlistPhoto }}" class="img-fluid" alt="...">
                                </a>
                                <div class="card-body px-2">
                                    <!-- <div class="cb-judul"> -->
                                        <p class="">{!! Illuminate\Support\Str::limit(
                                            "$wishlistable->variety | $wishlistable->breeder | Pedigree | $wishlistable->size | $wishlistable->bloodline",
                                            25,
                                        ) !!}</p>
                                    <!-- </div> -->
                                    <div class="row" style="height:58px;">
                                        <div class="col-9">
                                            <p class="my-2" style=""><b>Rp. {{ number_format($wishlistable->harga_ikan, 0, '.', '.')}}</b></p>
                                        </div>
                                        <div class="col-3 text-end">
                                            <p>
                                            <i
                                            data-id="{{ $wishlistable->id_koi_stock }}"
                                            data-type="id_koi_stock"
                                            style="color:red;font-size:smaller" class="remove my-3 fa-solid fa-trash-can"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-lg-6">
                                            <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                                style="font-size: 70%">Question <span><i
                                                        class="fa-brands fa-whatsapp"></i></span></a>
                                        </div>
                                        <div class="col-6 col-lg-6">
                                            <a href="{{ url('koi_stok') . '/' . $wishlist->wishlistable_id }}"
                                                class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2"
                                                style="font-size: 70%">DETAIL <span><i
                                                        class="fa-solid fa-circle-chevron-right"></i></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                @endforelse
            </div>
        </div>

    @endsection
    @push('scripts')
        <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let currentTime = "{{ $now }}";
            let timerLabels = document.getElementsByClassName('countdown-label');
            let addedExtraTimeGroups = [];

            function isMobile() {
                const regex = /Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
                return regex.test(navigator.userAgent);
            }

            setInterval(function() {
                getCurrentNow();
            }, 700);

            function allTimer() {
                $.each(timerLabels, function(prefix, val) {
                    var addedExtraTime = $(val).attr('data-end-extratime');
                    var currentEndTime = $(val).attr('data-endtime');

                    startTimer(addedExtraTime, currentEndTime, val)
                })
            }

            setInterval(function() {
                getCurrentNow();
            }, 700);

            function getCurrentNow() {
                $.ajax({
                    type: 'GET',
                    contentType: false,
                    processData: false,
                    url: '/now',
                    beforeSend: function() {

                    },
                    success: function(res) {
                        currentTime = res;
                    },
                    error(err) {

                    }
                })
            }

            allTimer()

            function startTimer(addedExtraTime, currentEndTime, val) {
                var currTime = moment(currentTime)
                var end = moment(currentEndTime);
                var endTime = new Date(currentEndTime.replace(' ', 'T'))

                // Update the count down every 1 second
                var x = setInterval(function() {
                    // Get today's date and time and extend it
                    var now = new Date(currentTime.replace(' ', 'T'))

                    // Find the distance between now and the count down date
                    var duration = endTime - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(duration / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    hours = hours + (days * 24);
                    var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((duration % (1000 * 60)) / 1000);

                    // Display the result in the element with id="timer"
                    const hourString = `${hours < 10 ? '0' : ''}${hours}`;
                    const minuteString = `${minutes < 10 ? '0' : ''}${minutes}`;
                    const secondString = `${seconds < 10 ? '0' : ''}${seconds}`;
                    const timerString = `${hourString}:${minuteString}:${secondString}`;
                    $(val).html(timerString);

                    // If the count down is finished, finish the exam
                    if (duration < 0) {
                        $(val).html(`00:00:00`);

                        clearInterval(x);
                        startExtraTimer(addedExtraTime, val);
                    }
                }, 1000);
            }

            function startExtraTimer(addedExtraTime, val) {
                var currTime = moment()


                var id = $(val).attr('data-id');
                var idTitle = $(val).attr('id');

                addedExtraTimeGroups[id] = addedExtraTime;

                if (idTitle === `atas-${id}`) {
                    setInterval(function() {
                        autoDetailBid(id);
                    }, 2500);
                }

                // Update the count down every 1 second
                var x = setInterval(function() {
                    var id = $(val).attr('data-id');
                    var endTime = new Date(addedExtraTimeGroups[id].replace(' ', 'T'));

                    // Get today's date and time and extend it
                    var now = new Date(currentTime.replace(' ', 'T'));

                    // Find the distance between now and the count down date
                    var duration = endTime - now;
                    // duration = 0;

                    // console.log($(val))

                    // Time calculations for days, hours, minutes and seconds
                    // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((duration % (1000 * 60)) / 1000);

                    // Display the result in the element with id="timer"
                    const hourString = `${hours < 10 ? '0' : ''}${hours}`;
                    const minuteString = `${minutes < 10 ? '0' : ''}${minutes}`;
                    const secondString = `${seconds < 10 ? '0' : ''}${seconds}`;
                    const timerString = `${hourString}:${minuteString}:${secondString}`;
                    $(val).html(timerString);
                    var id = $(val).attr('id');
                    // $(`#countdown-title-${id}`).html(`Extra Time`);


                    // If the count down is finished, finish the exam
                    if (duration < 0) {
                        $(val).html(`00:00:00`);

                        // document.getElementById(`btn-bid-${id}`).disabled = true;
                        // document.getElementById("auto_bid").disabled = true;
                        // document.getElementById("buttonAutoBid").disabled = true;
                        // document.getElementById("buttonNormalBid").disabled = true;
                        clearInterval(x);
                    }
                }, 1000);
            }

            function autoDetailBid(idIkan) {
                urlGet = `/auction/${idIkan}/detail`;

                $.ajax({
                    type: 'GET',
                    contentType: false,
                    processData: false,
                    url: urlGet,
                    beforeSend: function() {

                    },
                    success: function(res) {
                        meMaxBid = res.meMaxBid;

                        if (res.maxBid !== null) {
                            currentMaxBid = parseInt(res.maxBid)
                        }

                        if (res.logBid !== null) {
                            nominalBid = parseInt(res.logBid.nominal_bid)
                        }

                        // var currentPriceHtml = $('#currentPrice').html();
                        // var formatedMaxBid = thousandSeparator(res.maxBid)
                        // $('#currentPrice').html(`${formatedMaxBid}`);

                        addedExtraTimeGroups[idIkan] = res.addedExtraTime;
                    },
                    error(err) {

                    }
                })
            }

            $(document).on('click', '.addcart', function(e) {
                var button = $(this);
                var productId = $(this).attr('data-id')
                // $(this).attr('disabled', true)
                // var output = document.querySelector("#output");
                $.ajax({
                    type: 'POST',
                    url: `/carts`,
                    data: {
                        jumlah: 1,
                        cartable_id: productId,
                        cartable_type: 'Product',
                    },
                    dataType: "json",
                    complete: function(res) {
                        if (isMobile()) {
                            document.location = '/storecart'
                        } else {
                            document.location = '/profil?section=store-cart'
                        }
                        // element.attr('class', 'fas fa-heart wishlist');
                        button.removeAttr('disabled')
                    },
                    error: function(error) {
                        console.log(error)
                        return false
                    }
                })
            });

            $(document).on('click', '.remove', function(e) {
                var element = $(e.currentTarget);
                var id = element.attr('data-id');
                var type = element.attr('data-type');
                var postData = {};

                if (type === 'id_ikan_lelang') {
                    postData = {id_ikan_lelang: id}
                }

                if (type === 'id_koi_stock') {
                    postData = {id_koi_stock: id}
                }

                if (type === 'id_produk') {
                    postData = {id_produk: id}
                }

                $.ajax({
                    type: 'DELETE',
                    url: `wishlists/${id}`,
                    data: postData,
                    dataType: "json",
                    success: function(res) {
                        location.reload()
                        // $.map(targetElements, function(item) {
                        //     $(item).attr('class', `far fa-heart wishlist ${idClass}`);
                        // })

                        // return true;
                    },
                    error: function(error) {
                        console.log(error)
                        return false
                    }
                })
            })
        </script>
    @endpush
