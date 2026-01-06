@extends('layout.main')

@section('container')
    <style>
        @media screen and (min-width: 601px) {
            .res {
                display: none
            }
        }

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
            <div class="p-2 text-center">
                <p class="m-0" style="font-size: medium">CURRENT TOTAL BID</p>
                <h5 class="m-0 text-danger" id="current-total-prize" style="font-size: x-large">{{ number_format($currentTotalPrize, 0, '.', '.') }}</h5>
            </div>
        </div>
    @endif
    <br>
    <br>
    <div class="mb-10"></div>
    @if ($currentAuction && $currentAuction->kategori_event === 'Event')
        @php
            $bannerImg = 'img/event.webp';

            if ($currentAuction->banner !== null) {
                $bannerImg = url('storage') . '/' . $currentAuction->banner;
            }
        @endphp

        <div class="container-fluid p-0 web">
        </br>
        </div>
        <div class="container res">
        <br>
        </div>
    @endif
    <div class="container" style="padding-top: 4%">
        @if (count($auctionProducts) > 0)
            <p class="m-0">{!! $currentAuction->rules_event ?? '' !!}</p>
        @endif

        @php
            $auctionTitle = 'Special';

            if ($currentAuction && $currentAuction->kategori_event === 'Event') {
                $auctionTitle = 'Event';
            }
        @endphp

        <div class="container-fluid">
            <div>
            </div>

            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3 mb-5">

                @forelse($auctionProducts as $auctionProduct)
                    @php
                        $photo = 'img/koi11.webp';
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

                        $isHighestBidder = false;
                        if ($auth !== null && $auctionProduct->maxBid !== null && $auctionProduct->maxBid->id_peserta === $auth->id_peserta) {
                            $isHighestBidder = true;
                        }
                    @endphp
                    <div class="col mt-3">
                        <div class="card">
                            <img src="{{ $photo }}" class="card-img-top" alt="..." loading="lazy">
                            <div class="card-body">
                                <div class="cb-judul">
                                    <h5 class="card-title">{!! Illuminate\Support\Str::limit(
                                        "$auctionProduct->variety | $auctionProduct->breeder | $auctionProduct->bloodline | $auctionProduct->size",
                                        22,
                                    ) !!}</h5>
                                </div>
                                <p class="m-0">Number of bids</p>

                                <div class="row">
                                    <div class="col-6">
                                        <p class="" style="color: red" id="bid-count-{{ $auctionProduct->id_ikan }}">{{ $auctionProduct->bid_details_count }}</p>
                                    </div>

                                    <div class="col-6 p-0" id="highest-bid-container-{{ $auctionProduct->id_ikan }}"  style="{{ $isHighestBidder ? '' : 'display: none;' }}">
                                        <div class="row">
                                            <div class="col-4 p-0 px-1 text-end">
                                                <i style="color:red" class="fa-solid fa-caret-down"></i>
                                            </div>
                                            <div class="col-8 p-0 pt-1">
                                                <p class="m-0" style="font-size:70%;color:red">HIGHEST BID</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 p-0 ps-lg-1">
                                        <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                        <p class="m-0" style="color: red;font-size:75%" id="current-price-{{ $auctionProduct->id_ikan }}">{{ $auctionProduct->currency->symbol }} {{ number_format($currentMaxBid, 0, '.', '.') }}</p>
                                    </div>
                                    <div class="col-6 p-0 pe-lg-1">
                                        <p class="m-0" id="countdown-title-{{ $auctionProduct->id_ikan }}"
                                            style="text-align: end;font-size:80%">Remaining Time</p>
                                        <p class="m-0 countdown-label" id="timer-{{ $auctionProduct->id_ikan }}"
                                            data-endtime="{{ $auctionProduct->event->tgl_akhir->toIso8601String() }}"
                                            data-end-extratime="{{ $auctionProduct->tgl_akhir_extra_time->toIso8601String() }}"
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
                    <img src="{{ url('img/no-auction.webp') }}" class="d-block w-100 mt-5" alt="ceklis">
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

        const timers = {};

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        function refreshAuctionData() {
            $.ajax({
                url: '/auction-data',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#current-total-prize').text(formatCurrency(data.currentTotalPrize));

                    data.auctionProducts.forEach(product => {
                        $('#current-price-' + product.id_ikan).text(product.currency.symbol + ' ' + formatCurrency(product.currentMaxBid));
                        $('#bid-count-' + product.id_ikan).text(product.bid_details_count);
                        
                        const highestBidContainer = $('#highest-bid-container-' + product.id_ikan);
                        if (highestBidContainer) {
                            highestBidContainer.toggle(product.is_highest_bidder);
                        }

                        const timerId = `timer-${product.id_ikan}`;
                        if (timers[timerId]) {
                            const newExtraTime = moment(product.tgl_akhir_extra_time);
                            if (newExtraTime.isAfter(timers[timerId].extraTime)) {
                                timers[timerId].extraTime = newExtraTime;
                                if (timers[timerId].finished) {
                                    timers[timerId].finished = false;
                                }
                            }
                        }
                        // ==============================
                    });
                },
                error: function(error) {
                    console.error('Error refreshing auction data:', error);
                }
            });
        }

        function updateAllTimers() {
            $('.countdown-label[data-endtime]').each(function() {
                const timerElement = $(this);
                const id = timerElement.attr('id');

                if (timers[id] && timers[id].finished) {
                    return;
                }
                
                if (!timers[id]) {
                    timers[id] = {
                        endTime: moment(timerElement.data('endtime')),
                        extraTime: moment(timerElement.data('end-extratime')),
                        isExtra: false,
                        finished: false
                    };
                }

                let now = moment();
                let targetTime = timers[id].isExtra ? timers[id].extraTime : timers[id].endTime;
                
                let duration = moment.duration(targetTime.diff(now));

                if (duration <= 0) {
                    if (!timers[id].isExtra) {
                        timers[id].isExtra = true;
                        duration = moment.duration(timers[id].extraTime.diff(now));
                        
                        if (duration > 0) {
                           autoDetailBid(timerElement.attr('id').replace('timer-', ''));
                        }
                    }
                }
                
                if (duration <= 0) {
                    timerElement.text("00:00:00");
                    timers[id].finished = true;
                    let productId = id.replace('timer-', '');
                    $('#btn-bid-' + productId).addClass('disabled').css('pointer-events', 'none');
                } else {
                    const days = Math.floor(duration.asDays());
                    const hours = duration.hours() + (days * 24);
                    const minutes = duration.minutes();
                    const seconds = duration.seconds();

                    const timerString =
                        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    timerElement.text(timerString);
                }
            });
        }

        async function autoDetailBid(idIkan) {
            const urlGet = `/auction/${idIkan}/detail?simple=yes`;
            try {
                const response = await $.get(urlGet);
                const timerId = `timer-${idIkan}`;
                
                if (response.addedExtraTime && timers[timerId]) {
                    const newExtraTime = moment(response.addedExtraTime);
                    if (newExtraTime.isAfter(timers[timerId].extraTime)) {
                        timers[timerId].extraTime = newExtraTime;
                        timers[timerId].finished = false;
                    }
                }
            } catch (error) {
                console.error("Error in autoDetailBid:", error);
            }
        }

        $(document).on('click', '.wishlist', async function(e) {
            const element = $(e.currentTarget);
            const elClass = element.attr('class');
            const id = element.attr('data-id');

            try {
                let response;
                if (elClass === 'far fa-heart wishlist') {
                    response = await fetch('/wishlists', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({ id_ikan_lelang: id })
                    });

                    if (response.ok) {
                        element.attr('class', 'fas fa-heart wishlist');
                    } else {
                        console.error('Failed to add to wishlist:', await response.text());
                    }
                } else if (elClass === 'fas fa-heart wishlist') {
                    response = await fetch(`/wishlists/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({ id_ikan_lelang: id })
                    });

                    if (response.ok) {
                        element.attr('class', 'far fa-heart wishlist');
                    } else {
                        console.error('Failed to remove from wishlist:', await response.text());
                    }
                }
            } catch (error) {
                console.error('Error during wishlist operation:', error);
            }
        });

        $(document).ready(function() {
            refreshAuctionData(); 
            setInterval(updateAllTimers, 1000); 
            setInterval(refreshAuctionData, 3000); 
        });
    </script>
@endpush