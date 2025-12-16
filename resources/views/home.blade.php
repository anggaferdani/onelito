@extends('layout.main')

@section('container')
    <style>
        /* On screens that are 992px or less, set the background color to blue */
        @media screen and (min-width: 601px) {
            .nav-atas {
                display: none
            }
        }

        /* On screens that are 600px or less, set the background color to olive */
        @media screen and (max-width: 600px) {
            .nav-samping {
                display: none;
            }

        }

        .cb-judul {
            height: 5rem;

        }

        .cb-judu {
            height: 3.5rem;
        }

        .cb-jud {
            height: 2.5rem;
        }

        body a {
            text-decoration: none
        }

        .card.champion .card-img-top {
            width: auto;
        }

        .grid {
            display: grid;
            grid-template-columns: auto auto auto auto;
            gap: 10px;
            justify-content: center;
        }


        .grid>div img {
            width: 100%;
            grid-area: 1/1/2/2;
            aspect-ratio: 1/1;
        }

        @media screen and (max-width:982px) {
            .grid {
                grid-template-columns: auto auto auto;
            }
        }

        @media screen and (max-width:764px) {
            .grid {
                grid-template-columns: auto auto;
            }
        }

        @media screen and (max-width:600px) {
            .grid {
                grid-template-columns: auto;
                padding-left: 5%;
                padding-right: 5%;
            }
        }
    </style>

    <br><br><br><br>
    <div id="carouselExampleControls" class="pt-2 carousel slide" data-bs-interval="3000" data-bs-ride="carousel">
        <div class="carousel-inner img-mh-300">
            @forelse($banners as $key => $val)
                @php
                    $banner = 'img/new-banner.jpg';

                    if ($val->banner !== null) {
                        $bannerImg = url('storage') . '/' . $val->banner;
                    }
                @endphp

                @if ($val->banner !== null)
                    <div class="carousel-item {{ $key === 0 ? '' : '' }}">
                        <div class="container-fluit" style="background-color:red;">
                            <img src="{{ $bannerImg }}" class="w-100" alt="..." loading="lazy">
                        </div>
                    </div>
                @endif
            @empty
            @endforelse

            @forelse($auctions as $key => $auction)
                @php
                    $bannerImg = 'img/event.png';

                    if ($auction->banner !== null) {
                        $bannerImg = url('storage') . '/' . $auction->banner;
                    }
                @endphp

                @if ($auction->banner !== null)
                    <div class="carousel-item {{ $key === 0 ? '' : '' }}">
                        <div class="container-fluit" style="background-color:red;">
                            <img src="{{ $bannerImg }}" class="w-100" alt="...">
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
            <div class="carousel-item active">
                <div class="container-fluit" style="background-color:red;">
                    <img src="img/new-banner.jpg" class="d-block w-100" alt="Frame">
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container mt-3 mt-lg mt-lg-5">
        <div class="row g-2">
            <div class="col-md-4">
                <div class="card border rounded rounded-5" style="height: 125px;">
                    <div class="card-body rounded rounded-5" style="background-position: center; background-size: cover; background-image: url({{ asset('img/onelito-auction.jpg') }});">
                        <a href="/auction" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border rounded rounded-5" style="height: 125px;">
                    <div class="card-body rounded rounded-5" style="background-position: center; background-size: cover; background-image: url({{ asset('img/onelito-store.jpg') }});">
                        <a href="/onelito_store" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border rounded rounded-5" style="height: 125px;">
                    <div class="card-body rounded rounded-5" style="background-position: center; background-size: cover; background-image: url({{ asset('img/koi-store.jpg') }});">
                        <a href="/koi_stok" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="container nav-atas">
        <div class="row">
            @forelse($auctionProducts as $auctionProduct)
                @php
                    $photo = 'img/koi11.jpg';
                    if ($auctionProduct->photo !== null) {
                        $photo = url('storage') . '/' . $auctionProduct->photo->path_foto;
                    }

                    $currentMaxBid = $auctionProduct->ob;

                    if ($auctionProduct->maxBid !== null) {
                        $currentMaxBid = $auctionProduct->maxBid->nominal_bid;
                    }
                @endphp
                <div class="col-6 col-lg-3 mt-3">
                    <div class="card">
                        <a class="text-dark" href="/auction">
                            <img src="{{ $photo }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <div class="cb-jud">
                                    <h5 class="card-title">{!! Illuminate\Support\Str::limit(
                                        "$auctionProduct->variety | $auctionProduct->breeder | $auctionProduct->size | $auctionProduct->bloodline",
                                        45,
                                    ) !!}</h5>
                                </div>
                                <p class="m-0">Number of bids</p>
                                <p class="" style="color: red">{{ $auctionProduct->bid_details_count }}</p>
                                <div class="row">
                                    <div class="col-6 p-0 ps-lg-1">
                                        <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                        <p class="m-0" style="color: red;font-size:75%">{{ $auctionProduct->currency->symbol }} {{ number_format($currentMaxBid, 0, '.', '.') }}</p>
                                    </div>
                                    <div class="col-6 p-0 pe-lg-1">
                                        <p class="m-0 countdown-title-{{ $auctionProduct->id_ikan }}"
                                            style="text-align: end;font-size:80%">Remaining Time</p>
                                        <p class="m-0 countdown-label" data-id="{{ $auctionProduct->id_ikan }}"
                                            id="atas-{{ $auctionProduct->id_ikan }}"
                                            data-endtime="{{ $auctionProduct->event->tgl_akhir }}"
                                            data-end-extratime="{{ $auctionProduct->tgl_akhir_extra_time }}"
                                            style="text-align: end;color :red;font-size:75%;">00:00:00</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <img src="{{ url('img/lelang.png') }}" class="d-block w-100 mt-5" alt="ceklis">
            @endforelse
        </div>
    </div>

    <div class="container nav-samping">
        <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
            @forelse($auctionProducts as $auctionProduct)
                @php
                    $photo = 'img/koi11.jpg';
                    if ($auctionProduct->photo !== null) {
                        $photo = url('storage') . '/' . $auctionProduct->photo->path_foto;
                    }

                    $currentMaxBid = $auctionProduct->ob;

                    if ($auctionProduct->maxBid !== null) {
                        $currentMaxBid = $auctionProduct->maxBid->nominal_bid;
                    }
                @endphp
                <div class="col">
                    <a class="text-dark" href="/auction">
                        <div class="card">
                            @php
                                $photo = 'img/koi11.jpg';
                                if ($auctionProduct->photo !== null) {
                                    $photo = url('storage') . '/' . $auctionProduct->photo->path_foto;
                                }
                            @endphp
                            <img src="{{ $photo }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <div class="cb-judu">
                                    <h5 class="card-title"> {!! Illuminate\Support\Str::limit(
                                        "$auctionProduct->variety | $auctionProduct->breeder | Pedigree | $auctionProduct->size | $auctionProduct->bloodline",
                                        22,
                                    ) !!}
                                    </h5>
                                </div>
                                <p class="m-0">Number of bids</p>
                                <p class="" style="color: red">{{ $auctionProduct->bid_details_count }}</p>
                                <div class="row">
                                    <div class="col-6 p-0 ps-lg-1">
                                        <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                        <p class="m-0" style="color: red;font-size:75%">{{ $auctionProduct->currency->symbol }} {{ number_format($currentMaxBid, 0, '.', '.') }}</p>
                                    </div>
                                    <div class="col-6 p-0 pe-lg-1">
                                        <p class="m-0 countdown-title-{{ $auctionProduct->id_ikan }}"
                                            style="text-align: end;font-size:80%">Remaining Time</p>
                                        <p class="m-0 countdown-label" data-id="{{ $auctionProduct->id_ikan }}"
                                            id="bawah-{{ $auctionProduct->id_ikan }}"
                                            data-endtime="{{ $auctionProduct->event->tgl_akhir }}"
                                            data-end-extratime="{{ $auctionProduct->tgl_akhir_extra_time }}"
                                            style="text-align: end;color :red;font-size:75%;">00:00:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <img src="{{ url('img/lelang.png') }}" class="d-block w-100 mt-5" alt="ceklis">
            @endforelse
        </div>
    </div>

    <div class="container mt-5">
    </div> --}}

    <div class="container mt-5 text-center">
        <h3>ONELITO <span style="color:red;">KOI</span></h3>
        <br>
    </div>
    <div class="container">
        <p class="text-center" style="font-size:4rem;font-weight:400;">-- Make Hobbyist Happy --</p>
        <p class="text-center">Since 2021</p>
    </div>

    {{-- web --}}
    <div class="container">
        <div class="row" style="display: flex; justify-content: space-between">
            <div class="col-lg-2 col-12 mt-4 col-md-6" style="display: flex; justify-content: center">
                <a href="mailto:onelito.koi@gmail.com"><img src="{{ url('img/email.png') }}" alt="email"></a>
            </div>
            <div class="col-lg-2 col-12 mt-4 col-md-6" style="display: flex; justify-content: center">
                <a href="https://www.tokopedia.com/onelitokoi" target="_blank"><img src="{{ url('img/tokped.png') }}"
                        alt="tokped"></a>
            </div>
            <div class="col-lg-2 col-12 mt-4 col-md-6" style="display: flex; justify-content: center">
                <a href="https://www.google.com/maps/place/Onelito+Koi/@-6.3258102,106.6893418,17z/data=!3m1!4b1!4m5!3m4!1s0x2e69fb6e6c391ec5:0x724a9e4e6c80aaed!8m2!3d-6.3258155!4d106.6915305"
                    target="_blank"><img src="{{ url('img/alamat.png') }}" alt="alamat"></a>
            </div>
            <div class="col-lg-2 col-12 mt-4 col-md-6" style="display: flex; justify-content: center">
                <div class="">
                    <a href="https://api.whatsapp.com/send?phone=6282124425038&text=Halo%20saya%20ingin%20bertanya%20mengenai%20*Onelito%20Koi*"
                        target="_blank"><img src="{{ url('img/wa.png') }}" alt="wa"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluit m-5">
        <img src="img/gc.png" alt="gc" class="w-100">
    </div>

    <div class="container grid">
        @forelse($championFishes as $championFish)
            @php
                $photoChampion = 'img/koi12.jpg';
                if ($championFish->foto_ikan !== null) {
                    $photoChampion = url('storage') . '/' . $championFish->foto_ikan;
                }
            @endphp
            <div class="d-grid">
                <img src="{{ $photoChampion }}" class="" alt="..." loading="lazy">
            </div>
        @empty
        @endforelse
    </div>
    <div class="container my-5">
        <a href="/detail_koichampion" style="color: red">Lihat lebih Banyak >></a>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let currentTime = new Date("{{ $now }}");

        setInterval(() => {
            currentTime = new Date(currentTime.getTime() + 1000);
        }, 1000);

        let timerLabels = document.getElementsByClassName('countdown-label');
        let addedExtraTimeGroups = [];

        function allTimer() {
            $.each(timerLabels, function(prefix, val) {
                var addedExtraTime = $(val).attr('data-end-extratime');
                var currentEndTime = $(val).attr('data-endtime');
                startTimer(addedExtraTime, currentEndTime, val)
            })
        }

        allTimer()

        let timers = {};

        function startTimer(addedExtraTime, currentEndTime, val) {
            let id = $(val).data('id');
            if (timers[id]) return;

            let endTime = new Date(currentEndTime.replace(' ', 'T'));

            timers[id] = setInterval(() => {
                let now = currentTime;
                let duration = endTime - now;

                if (duration <= 0) {
                    clearInterval(timers[id]);
                    delete timers[id];
                    startExtraTimer(addedExtraTime, val);
                    return;
                }

                let hours = Math.floor(duration / (1000 * 60 * 60));
                let minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((duration % (1000 * 60)) / 1000);

                $(val).html(
                    `${hours.toString().padStart(2, '0')}:` +
                    `${minutes.toString().padStart(2, '0')}:` +
                    `${seconds.toString().padStart(2, '0')}`
                );
            }, 1000);
        }

        function startExtraTimer(addedExtraTime, val) {
            var id = $(val).attr('data-id');
            var idTitle = $(val).attr('id');

            addedExtraTimeGroups[id] = addedExtraTime;

            if (idTitle === `atas-${id}`) {
                autoDetailBid(id);
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
            if (!addedExtraTimeGroups[idIkan]) return;

            $.ajax({
                type: 'GET',
                url: `/auction/${idIkan}/detail?simple=yes`,
                success: function(res) {
                    addedExtraTimeGroups[idIkan] = res.addedExtraTime;
                }
            });
        }

        $(document).on('click', '.wishlist', function(e) {
            var element = $(e.currentTarget);
            var elClass = element.attr('class');
            var id = element.attr('data-id');

            if (elClass === 'far fa-heart wishlist') {
                $.ajax({
                    type: 'POST',
                    url: `wishlists`,
                    data: {
                        id_produk: id
                    },
                    dataType: "json",
                    success: function(res) {
                        element.attr('class', 'fas fa-heart wishlist');

                        return true;
                    },
                    error: function(error) {
                        console.log(error)
                        return false
                    }

                })
            }

            if (elClass === 'fas fa-heart wishlist') {
                $.ajax({
                    type: 'DELETE',
                    url: `wishlists/${id}`,
                    data: {
                        id_produk: id
                    },
                    dataType: "json",
                    success: function(res) {
                        element.attr('class', 'far fa-heart wishlist');
                        return true;
                    },
                    error: function(error) {
                        console.log(error)
                        return false
                    }
                })
            }
        })
    </script>
@endpush
