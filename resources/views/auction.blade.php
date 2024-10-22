@extends('layout.main')

@section('container')
    <style>
        /* On screens that are 992px or less, set the background color to blue */
        @media screen and (min-width: 601px) {
            .res {
                display: none
            }
        }

        /* On screens that are 600px or less, set the background color to olive */
        @media screen and (max-width: 600px) {
            .web {
                display: none;
            }
        }

        .bottom {
            position: absolute;
            margin-top: 15.5%;
            width: 99%;

        }

        .bottom-banner {
            margin-top: -5.3%;
        }


        @media screen and (max-width: 600px) {
            .card-body {
                min-height: 217px;
            }

            .card-title {
                min-height: 26px;
            }

            .bottom-banner {
                margin-top: inherit;
            }

            .banner {
                height: 150px;
            }

            .bottom-banner .card {
                max-height: 55px;
            }

            .currentmaxbid {
                margin-top : -2%;
            }

        }

        .cb-judul {
            height: 3.5rem;

        }

        .currentmaxbid {
            position: fixed;
            width: 100vw;
            z-index: 98;
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 0.5rem;
        }

        @media screen and (max-width: 600px) {
            .nav-samping {
                display: none;
            }

        }
    </style>

    <br><br><br><br>
    @if (count($auctionProducts) > 0)
                    <div class="row justify-content-center currentmaxbid">
                        <!-- <div class="col"> -->
                            <!-- <div class="card"> -->
                                <div class="p-2 text-center">
                                    <p class="m-0" style="font-size: medium">CURRENT TOTAL BID</p>
                                    <h5 class="m-0 text-danger" style="font-size: x-large">{{ number_format($currentTotalPrize, 0, '.', '.') }}</h5>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
            @endif
    <br>
    <br>
    <div class="mb-10"></div>
    @if ($currentAuction && $currentAuction->kategori_event === 'Event')
        @php
            $bannerImg = 'img/event.png';

            if ($currentAuction->banner !== null) {
                $bannerImg = url('storage') . '/' . $currentAuction->banner;
            }
        @endphp



        <div class="container-fluid p-0 web">
            <!-- <div class="row w-100 m-0 mb-3">
                <div id="carouselExampleControls" class="pt-2 carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner img-mh-300">
                            @forelse ($auctions as $key => $auction)
                                @php
                                    $bannerImg = 'img/event.png';

                                    if ($auction->banner !== null) {
                                        $bannerImg = url('storage') . '/' . $auction->banner;
                                    }
                                @endphp
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <div class="container-fluit" style="background-color:red;">
                                        <img src="{{ $bannerImg }}" class="w-100" alt="...">
                                    </div>
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <div class="container-fluit" style="background-color:red;">
                                        <img src="{{ $bannerImg }}" class="w-100" alt="...">
                                    </div>
                                </div>
                            @endforelse

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                </div> -->
                <!-- <div class="col-12">
                    <img src="{{ $bannerImg }}" class="w-100" alt="...">
                </div> -->
            <!-- </div> -->
            <!-- <div class="bottom col-12"> -->


        </br>
            <!-- </div> -->
        </div>
        <div class="container res">

        <br>
        </div>
    @endif
    <div class="container" style="padding-top: 4%">
        @if (count($auctionProducts) > 0)
            <p class="m-0">{!! $currentAuction->rules_event ?? '' !!}</p>
        @endif

            {{-- <div class="my-5">
                <p style="color: red">{{ $currentAuction->deskripsi_event ?? "" }}</p>
            </div> --}}

        @php
            $auctionTitle = 'Special';

            if ($currentAuction && $currentAuction->kategori_event === 'Event') {
                $auctionTitle = 'Event';
            }

        @endphp

        <div class="container-fluid">
            <div>
                {{-- <h5>{{ $auctionTitle }} Auction</h5>

                <img src="{{ url('img/nolelang.png') }}" class="d-block w-100" alt="ceklis"> --}}
            </div>



            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3 mb-5">

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

                        $wishlistClass = 'far fa-heart';

                        if (array_key_exists('wishlist', $auctionProduct->toArray()) && $auctionProduct->wishlist !== null) {
                            $wishlistClass = 'fas fa-heart';
                        }
                    @endphp
                    <div class="col mt-3">
                        <div class="card">
                            <img src="{{ $photo }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                {{-- <h5 class="card-title">{{ $auctionProduct->variety }} | {{ $auctionProduct->breeder }} | {{ $auctionProduct->bloodline }} | {{ $auctionProduct->size }}</h5> --}}
                                <div class="cb-judul">
                                    <h5 class="card-title">{!! Illuminate\Support\Str::limit(
                                        "$auctionProduct->variety | $auctionProduct->breeder | $auctionProduct->bloodline | $auctionProduct->size",
                                        22,
                                    ) !!}</h5>
                                </div>
                                <p class="m-0">Number of bids</p>

                                <div class="row">
                                    <div class="col-6">
                                        <p class="" style="color: red">{{ $auctionProduct->bid_details_count }}</p>
                                    </div>

                                    <div class="col-6 p-0">
                                    @if ($auth !== null)
                                        @if ($auctionProduct->maxBid !== null)
                                            @if ($auctionProduct->maxBid->id_peserta === $auth->id_peserta)
                                                <div class="row">
                                                    <div class="col-4 p-0 px-1 text-end">
                                                        <i style="color:red" class="fa-solid fa-caret-down"></i>
                                                    </div>
                                                    <div class="col-8 p-0 pt-1">
                                                        <p class="m-0" style="font-size:70%;color:red">HIGHEST BID</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 p-0 ps-lg-1">
                                        <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                        <p class="m-0" style="color: red;font-size:75%">{{ $auctionProduct->currency->symbol }} {{ number_format($currentMaxBid, 0, '.', '.') }}</p>
                                    </div>
                                    <div class="col-6 p-0 pe-lg-1">
                                        <p class="m-0" id="countdown-title-{{ $auctionProduct->id_ikan }}"
                                            style="text-align: end;font-size:80%">Remaining Time</p>
                                        <p class="m-0 countdown-label" id="{{ $auctionProduct->id_ikan }}"
                                            data-endtime="{{ $auctionProduct->event->tgl_akhir }}"
                                            data-end-extratime="{{ $auctionProduct->tgl_akhir_extra_time }}"
                                            style="text-align: end;color :red;font-size:75%;">00:00:00</p>
                                    </div>
                                    <div class="col-12 p-2 px-lg-2">
                                        <div class="row">
                                            <div class="col-6 col-md-6 p-0 px-sm-2">
                                                <a id="btn-bid-{{ $auctionProduct->id_ikan }}"
                                                    href="{{ '/auction-bid-now/' . $auctionProduct->id_ikan }}"
                                                    class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                                    style="font-size: 80%">BID NOW <span><i
                                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                                            </div>
                                            <div class="col-6 col-md-6 pe-0 px-sm-2">
                                                <a href="{{ '/auction/' . $auctionProduct->id_ikan }}"
                                                    class="btn btn-secondary w-100 d-flex justify-content-between px-1 p-1"
                                                    style="font-size: 80%">DETAIL <span><i
                                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                                            </div>
                                            <div class="col-9 mt-2 px-2">
                                                <a target="_blank" href="{{ $auctionProduct->link_video }}"
                                                    class="btn btn-light w-100 d-flex justify-content-between">VIDEO
                                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                            </div>
                                            <div class="col-3 ">
                                                <button class="border-0 mt-2"
                                                    style="background-color: transparent;font-size:larger; float: right"><i
                                                        data-id="{{ $auctionProduct->id_ikan }}"
                                                        class="{{ $wishlistClass }} wishlist"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <img src="{{ url('img/lelang.png') }}" class="d-block w-100 mt-5" alt="ceklis">
                @endforelse
            </div>
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

        getCurrentNow();

        async function getCurrentNow()
        {
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
                complete: function() {
                    setTimeout(() => {
                        getCurrentNow()
                    }, 800);
                },
                error(err) {

                }
            })
        }

        function allTimer() {
            $.each(timerLabels, function(prefix, val) {
                var addedExtraTime = $(val).attr('data-end-extratime');
                var currentEndTime = $(val).attr('data-endtime');

                startTimer(addedExtraTime, currentEndTime, val)
            })
        }

        allTimer()

        function startTimer(addedExtraTime, currentEndTime, val) {
            // var currTime = moment(currentTime)
            // var endTime = moment(currentEndTime);
            var endTime = new Date(currentEndTime.replace(' ', 'T'));

            // Update the count down every 1 second
            var x = setInterval(function() {
                // Get today's date and time and extend it
                var now = new Date(currentTime.replace(' ', 'T'));
                // var now = moment(currentTime)

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


            var id = $(val).attr('id');

            addedExtraTimeGroups[id] = addedExtraTime;

            if (addedExtraTime > currentTime) {
                setTimeout(() => {
                    autoDetailBid(id)
                }, 20000);
            }

            // Update the count down every 1 second
            var x = setInterval(function() {
                var id = $(val).attr('id');
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

        async function autoDetailBid(idIkan) {
            urlGet = `/auction/${idIkan}/detail?simple=yes`;

            $.ajax({
                type: 'GET',
                contentType: false,
                processData: false,
                url: urlGet,
                beforeSend: function() {

                },
                success: function(res) {
                    // var currentPriceHtml = $('#currentPrice').html();
                    // var formatedMaxBid = thousandSeparator(res.maxBid)
                    // $('#currentPrice').html(`${formatedMaxBid}`);

                    addedExtraTimeGroups[idIkan] = res.addedExtraTime;

                    if (res.addedExtraTime > currentTime) {
                        setTimeout(() => {
                            autoDetailBid(idIkan)
                        }, 20000);
                    }
                },
                complete: function() {

                },
                error(err) {

                }
            })
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
                        id_ikan_lelang: id
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
                        id_ikan_lelang: id
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
