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
            $bannerImg = 'img/event.png';

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

                        $isHighestBidder = false;
                        if ($auth !== null && $auctionProduct->maxBid !== null && $auctionProduct->maxBid->id_peserta === $auth->id_peserta) {
                            $isHighestBidder = true;
                        }
                    @endphp
                    <div class="col mt-3">
                        <div class="card">
                            <img src="{{ $photo }}" class="card-img-top" alt="...">
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
                    <img src="{{ url('img/no-auction.jpg') }}" class="d-block w-100 mt-5" alt="ceklis">
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

        let currentTime = moment(); // Use moment.js for current time
        let timerLabels = document.getElementsByClassName('countdown-label');
        let addedExtraTimeGroups = {}; // Use an object to store extra times

        // Function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR', // Change to your currency if needed
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // --- START : Real-time Data Updates ---
        // Refreshes real-time data (Current Bid, Number of Bids, Highest Bidder Indicator)
        function refreshAuctionData() {
            $.ajax({
                url: '/auction-data', // Replace with your actual route
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Update Current Total Bid
                    $('#current-total-prize').text(formatCurrency(data.currentTotalPrize));

                    // Update individual product data
                    data.auctionProducts.forEach(product => {
                        // Update "Harga saat ini"
                        $('#current-price-' + product.id_ikan).text(product.currency.symbol + ' ' + formatCurrency(product.currentMaxBid));

                        // Update "Number of bids"
                        $('#bid-count-' + product.id_ikan).text(product.bid_details_count);

                        // Update Highest Bidder Indicator
                        const highestBidContainer = $('#highest-bid-container-' + product.id_ikan);
                        if (highestBidContainer) {
                            if (product.is_highest_bidder) {
                                highestBidContainer.show(); // Show if the user is the highest bidder
                            } else {
                                highestBidContainer.hide(); // Hide if the user is not the highest bidder
                            }
                        }
                    });
                },
                error: function(error) {
                    console.error('Error refreshing auction data:', error);
                }
            });
        }

        // Refresh data every 3 seconds (adjust interval as needed)
        setInterval(refreshAuctionData, 3000);

        // --- END : Real-time Data Updates ---



        // --- START :  Countdown Timers  ---

        // Function to initialize all timers
        function initializeTimers() {
            Array.from(timerLabels).forEach(timerLabel => { // Use Array.from to work with HTMLCollection
                const id = timerLabel.id;
                const currentEndTime = timerLabel.dataset.endtime;
                const addedExtraTime = timerLabel.dataset.endExtratime;

                if (currentEndTime) { // Ensure the end time is valid
                    startTimer(id, addedExtraTime, currentEndTime);
                }
            });
        }

        // Function to start or update a single timer
        function startTimer(id, addedExtraTime, currentEndTime) {
            // Clear any existing interval for this timer to prevent multiple timers running.
            clearInterval(window[`timerInterval_${id}`]);

            let endTime = moment(currentEndTime.replace(' ', 'T')); // Use moment.js
            if (!endTime.isValid()) {
                console.error(`Invalid end time for ID ${id}: ${currentEndTime}`);
                return; // Exit if the end time is invalid
            }


            // Function to update the timer display
            function updateTimer() {
                let now = moment(); // Get the current time using moment.js
                let duration = moment.duration(endTime.diff(now));
                let days = duration.days();
                let hours = duration.hours();
                let minutes = duration.minutes();
                let seconds = duration.seconds();

                // Handle negative durations (time already passed)
                if (duration <= 0) {
                    document.getElementById(id).textContent = "00:00:00";
                    clearInterval(window[`timerInterval_${id}`]); // Clear the interval

                    if (addedExtraTime && !addedExtraTimeGroups[id]) {  // Only run once per extra time added
                        addedExtraTimeGroups[id] = true; // Prevent multiple calls
                        startExtraTimer(id, addedExtraTime);  // Start extra timer
                    }

                    return; // Exit the function
                }

                const hourString = String(hours + (days * 24)).padStart(2, '0'); // Include days in hours
                const minuteString = String(minutes).padStart(2, '0');
                const secondString = String(seconds).padStart(2, '0');
                document.getElementById(id).textContent = `${hourString}:${minuteString}:${secondString}`;
            }

            // Initial call to set the timer immediately
            updateTimer();

            // Set interval to update the timer every second
            window[`timerInterval_${id}`] = setInterval(updateTimer, 1000);
        }

        function startExtraTimer(id, addedExtraTime) {
            // Stop the extra timer if addedExtraTime is not valid
            if (!addedExtraTime) {
                console.warn(`No extra time provided for ID ${id}`);
                return;
            }

            let endTime = moment(addedExtraTime.replace(' ', 'T'));

            if (!endTime.isValid()) {
                console.error(`Invalid extra end time for ID ${id}: ${addedExtraTime}`);
                return;
            }

            // Call autoDetailBid after 10 seconds
            setTimeout(() => {
                autoDetailBid(id);
            }, 10000);


            // Function to update the timer display
            function updateExtraTimer() {
                let now = moment(); // Get the current time using moment.js
                let duration = moment.duration(endTime.diff(now));

                let hours = duration.hours();
                let minutes = duration.minutes();
                let seconds = duration.seconds();

                if (duration <= 0) {
                    document.getElementById(id).textContent = "00:00:00";
                    clearInterval(window[`extraTimerInterval_${id}`]);
                    return;
                }

                const hourString = String(hours).padStart(2, '0');
                const minuteString = String(minutes).padStart(2, '0');
                const secondString = String(seconds).padStart(2, '0');
                document.getElementById(id).textContent = `${hourString}:${minuteString}:${secondString}`;
            }

            updateExtraTimer();

            window[`extraTimerInterval_${id}`] = setInterval(updateExtraTimer, 1000);
        }

        // --- END :  Countdown Timers  ---



        // --- START :  Asynchronous Operations (Auto Bid, Wishlist) ---

        async function autoDetailBid(idIkan) {
            const urlGet = `/auction/${idIkan}/detail?simple=yes`;

            try {
                const response = await fetch(urlGet);
                const res = await response.json();

                if (res.addedExtraTime) {
                    // Convert addedExtraTime string to a Date object for comparison
                    const addedExtraTime = moment(res.addedExtraTime.replace(' ', 'T'));
                    const now = moment();
                    if (addedExtraTime.isAfter(now)) { // Use moment.js comparison
                        // Re-initialize the timer with the updated end time.
                        const timerLabel = document.getElementById(idIkan);
                        if (timerLabel) {
                            timerLabel.dataset.endExtratime = res.addedExtraTime; // Update the attribute
                            startTimer(idIkan, res.addedExtraTime, timerLabel.dataset.endtime);
                        }
                    }
                }

                refreshAuctionData(); // Refresh data after potential bid update
            } catch (error) {
                console.error("Error in autoDetailBid:", error);
            }
        }

        // --- END :  Asynchronous Operations (Auto Bid, Wishlist) ---


        // --- START : Wishlist Functionality ---
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
        // --- END : Wishlist Functionality ---

        // --- START : Page Initialization ---

        // Initialize the timers when the page loads.
        initializeTimers();

        // --- END : Page Initialization ---
    </script>
@endpush