@extends('layout.main')

@section('container')
    <style>
        @media screen and (max-width: 600px) {
            .nav-samping {
                display: none;
            }

        }

        .swal2-cancel {
            margin-right: 10px;
        }
    </style>

    <br><br><br><br>
    @php
        $previous = url()->previous();
    @endphp

    <div class="container">
        <div class="modal fade" id="exampleModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">History Bidding</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ $previous }}"><i class="fa-solid fa-arrow-left-long text-body" style="font-size: x-large"></i></a>

        <style>
            @media screen and (min-width: 601px) {}

            @media screen and (max-width: 600px) {
                hr {
                    margin: 0;
                }

                p {
                    font: size 11px !important;
                    margin-bottom: 0.5rem;
                }

                h3 {
                    font-size: 12px !important;
                    margin-bottom: 0;
                }

                button.btn-danger {
                    font-size: 13px;
                    height: 38px;
                }
            }
        </style>

        <div class="web">
            <div class="row gx-5">
                <div class="col-6 col-md-4">
                    <div class="m-lg-auto" style="max-width: 18rem;">
                        @php
                            $imgUrl = 'img/koi11.jpg';

                            if ($auctionProduct->photo) {
                                $imgUrl = 'storage/' . $auctionProduct->photo->path_foto;
                            }
                        @endphp
                        <img src="{{ url($imgUrl) }}" class="card-img-top" alt="...">
                        <br><br>
                        <div class="card-body p-0">
                            <a target="_blank" href="{{ $auctionProduct->link_video }}"
                                class="btn btn-danger w-100 d-flex justify-content-between" style="font-size:larger">VIDEO
                                <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-8 ps-0">
                    <p style="font-size: larger">Auction Detail</p>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <h3>
                                <table>
                                    <tr>
                                        <td>Variety</td>
                                        <!-- <td>: Kohaku</td> -->
                                        <td>: {{ $auctionProduct->variety }}</td>
                                    </tr>
                                    <tr>
                                        <td>Breeder</td>
                                        <!-- <td>: Sakai Fish Farm</td> -->
                                        <td>: {{ $auctionProduct->breeder }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bloodline</td>
                                        <!-- <td>: S Legend</td> -->
                                        <td>: {{ $auctionProduct->bloodline }}</td>
                                    </tr>
                                </table>
                            </h3>
                        </div>
                        <div class="col">
                            <h3>
                                <table>
                                    <tr>
                                        <td>Sex</td>
                                        <!-- <td>: Female</td> -->
                                        <td>: {{ $auctionProduct->sex }}</td>
                                    </tr>
                                    <tr>
                                        <td>DOB</td>
                                        <!-- <td>: 2020</td> -->
                                        <td>: {{ $auctionProduct->dob }}</td>
                                    </tr>
                                    <tr>
                                        <td>Size</td>
                                        <!-- <td>: 57 cm</td> -->
                                        <td>: {{ $auctionProduct->size }}</td>
                                    </tr>
                                </table>
                            </h3>
                        </div>
                    </div>
                    <hr>

                    <p class="m-0" style="font-size: larger">Note :</p>
                    <p style="font-size: larger">{!! $auctionProduct->note !!}</p>
                    <hr>

                    <p style="font-size:30px">Harga saat ini:
                        <span class="alert-link text-danger">{{ $auctionProduct->currency->symbol }} </span>
                        <span id="currentPrice"
                            class="alert-link text-danger number-separator">{{ number_format($currentPrice, 0, '.', '.') }}</span>
                    </p>
                    <hr>

                    <p style="font-size:25px">Kelipatan BID: <span
                            class="alert-link text-danger">{{ $auctionProduct->currency->symbol }}
                            {{ number_format($auctionProduct->kb, 0, '.', '.') }}</span></p>
                    <hr>
                    <div class="row d-flex">
                        <p class="m-0" style="font-size: larger">Remaining Time &nbsp;
                            <span id="countdown-extra" class="m-0 text-danger d-none"></span>
                        </p>
                    </div>
                    <p class="alert-link text-danger countdown-label" style="font-size: 30px">00 : 00 : 00</p>

                    <br><br>
                </div>

                <div class="row m-1">
                    <div class="col-md-4">
                    </div>
                    @auth('member')
                        <div class="col-12 col-md-8 no-gutters">
                            <form method="" id="" action="" class="row">
                                @csrf
                                <div class="col-7 col-md-9" style="padding-right:0px">
                                    <input type="text" id="" name="" value=""
                                        class="d-none form-control number-separator">
                                </div>
                                <div class="col-5 col-md-3" style="padding-left:0px; max-height: 38px">
                                    <button id="buttonHistoryBidding" type="button"
                                        class="btn btn-danger w-100 justify-content-between" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">HISTORY BIDDING</button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>

                @if ($addedExtraTime >= $now)
                    <div class="row m-1">
                        <div class="col-md-4">
                        </div>
                        @auth('member')
                            <div class="col-12 col-md-8 no-gutters">
                                <form method="POST" id="normalBidForm" action="/auction/{{ $idIkan }}" class="row">
                                    @csrf
                                    <div class="col-7 col-md-9" style="padding-right:0px">
                                        <!-- <input type="text" id="nominal_bid2" name="nominal_bid2" value="{{ $logBid->nominal_bid ?? '' }}" class="form-control number-separator" id="exampleFormControlInput1" placeholder="Nominal BID"> -->
                                        <input type="text" id="nominal_bid" name="nominal_bid" value="" required
                                            class="form-control number-separator" id="exampleFormControlInput1"
                                            placeholder="Nominal BID">
                                    </div>
                                    <div class="col-5 col-md-3" style="padding-left:0px; max-height: 38px">
                                        <button id="buttonNormalBidSubmit" type="submit" hidden class="d-none"></button>
                                        <button id="buttonNormalBid" type="button" onclick="clickyakin()"
                                            class="btn btn-danger w-100 justify-content-between">BID AUCTION</button>
                                        <button hidden onclick="cancelAutoBid()" id="buttonCancelAutoBid" type="button"
                                            class="btn btn-danger mb-3 w-100 justify-content-between">CANCEL AUTO BID</button>
                                    </div>
                                </form>
                                <div class="alert alert-danger bid alert-dismissible fade mb-0 mt-3" role="alert">
                                </div>
                            </div>
                        @endauth
                    </div>

                    <div class="row m-1 d-none">
                        <div class="col-md-4 no-gutters">
                        </div>
                        @auth('member')
                            <div class="col-12 col-md-8 no-gutters">
                                <form method="POST" id="autoBidForm" action="/auction/{{ $idIkan }}" class="row">
                                    <div class="col-7 col-md-9" style="padding-right:0px">
                                        <!-- <input type="text" id="auto_bid2" name="auto_bid2" class="form-control" value="{{ $logBid->auto_bid ?? '' }}" id="exampleFormControlInput1" placeholder="Nominal Max Auto BID"> -->
                                        <input type="text" id="auto_bid" name="auto_bid" class="form-control"
                                            value="" id="exampleFormControlInput1" placeholder="Nominal Max Auto BID">
                                    </div>
                                    <div class="col-5 col-md-3" style="padding-left:0px">
                                        <button type="submit" id="buttonAutoBid"
                                            class="btn btn-danger w-100 justify-content-between">
                                            AUTO BID
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <br><br><br>
    </div>
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/js/price-separator.min.js') }}"></script>
    <script src="{{ asset('/library/lodash/lodash.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        let currentMaxBid = @json($maxBid);
        let nominalBid = @json($logBid);
        let autoBid = @json($autoBid);
        let idIkan = @json($idIkan);
        let auctionProduct = @json($auctionProduct);
        let currency = auctionProduct.currency.symbol;
        let statusGetMaxBid = false;
        let statusAutoBid = false;
        let meMaxBid = false;
        let currentTime = "{{ $now }}";
        let addedExtraTime = "{{ $addedExtraTime }}";
        let currentEndTime = auctionProduct.event.tgl_akhir;
        let lastUpdateBid = @json($maxBidData)

        this.bidding = _.debounce(this.bidding, 1000);

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        function clickyakin() {
            var nominal = $('#nominal_bid').val();

            swalWithBootstrapButtons.fire({
                title: `Apakah anda benar ingin
                Bidding ${currency} ${nominal} ?`,
                text: ``,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#buttonNormalBidSubmit").click();

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Batal',
                        'Bid anda dibatalkan',
                        'error'
                    )

                    $('#nominal_bid').val('')
                }
            })
        }


        function thousandSeparator(x) {
            var reverse = x.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');

            return ribuan
        }

        // if ($('#nominal_bid').length !== 0) {
        $('#nominal_bid').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });
        // }

        // if ($('#auto_bid').length !== 0) {
        $('#auto_bid').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });
        // }

        // function normalBidFormSubmit(e) {
        $('#normalBidForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = $(this).attr('action')
            let kb = parseInt(auctionProduct.kb);

            var inputNominalBid = parseInt($('#nominal_bid').unmask());

            // if (inputNominalBid <= currentMaxBid) {
            //     $('.alert.bid').html(`Nominal bid tidak boleh dibawah harga saat ini`);

            //     $('.alert.bid').addClass('show');

            //     setTimeout(function() {
            //         $('.alert.bid').removeClass('show')
            //     }, 2000);

            //     return true;
            // }

            formData.set('nominal_bid', inputNominalBid)
            formData.append('nominal_bid_detail', inputNominalBid)

            bidding(formData, url);
        })

        function cancelAutoBid() {
            statusAutoBid = false;
            document.getElementById("nominal_bid").disabled = false;
            document.getElementById("auto_bid").disabled = false;
            $('#buttonCancelAutoBid').attr('hidden', 'hidden');
            $('#buttonNormalBid').removeAttr('hidden');
            $('#buttonAutoBid').html(`AUTO BID`)
        }

        $('#autoBidForm').submit(function(e) {
            document.getElementById("nominal_bid").disabled = true;
            document.getElementById("auto_bid").disabled = true;
            e.preventDefault();
            let formData = new FormData(this);
            let url = $(this).attr('action')
            let kb = parseInt(auctionProduct.kb);
            let inputNominalBid = parseInt($('#nominal_bid').unmask());
            let nextNominalBid = (kb + inputNominalBid);
            statusAutoBid = true;
            autoBid = parseInt($('#auto_bid').unmask());
            formData.append('nominal_bid', nextNominalBid)
            formData.append('auto_bid', autoBid)
        })

        async function bidding(formData, url) {
            formData.append('_token', '{{ csrf_token() }}')
            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                url: url,
                beforeSend: function() {

                },
                complete: function() {
                    // console.log({statusAutoBid, autoBid, nominalBid})
                    if (statusAutoBid === false) {
                        return false;
                    }

                    let kb = parseInt(auctionProduct.kb);

                    let inputNominalBid = parseInt($('#nominal_bid').unmask());

                    let nextNominalBid = (kb + inputNominalBid);

                    formData.set('nominal_bid', nextNominalBid)

                },
                success: function(res) {
                    $('#nominal_bid').val('')
                    nominalBid = formData.get('nominal_bid');
                    swalWithBootstrapButtons.fire(
                        'Berhasil',
                        'Bid sukses',
                        'success'
                    )
                    // autoBid = parseInt($('#auto_bid').val());

                    // if (nominalBid > currentMaxBid) {
                    //     statusAutoBid = false;
                    // }
                },
                error(err) {
                    statusAutoBid = false;
                    document.getElementById("nominal_bid").disabled = false;
                    document.getElementById("auto_bid").disabled = false;
                    $('.alert.bid').html(err.responseJSON.message);

                    $('.alert.bid').addClass('show');

                    setTimeout(function() {
                        $('.alert.bid').removeClass('show')
                    }, 2000);
                }
            })
        }

        function autoDetailBid() {
            urlGet = `/auction/${idIkan}/detail`;

            $.ajax({
                type: 'GET',
                contentType: false,
                processData: false,
                url: urlGet,
                beforeSend: function() {

                },
                complete: function() {
                    // console.log({currentMaxBid, autoBid, nominalBid, meMaxBid})
                    setTimeout(() => {
                        autoDetailBid()
                    }, 2000);

                    let formData = new FormData(document.getElementById("autoBidForm"));

                    let url = $('#autoBidForm').attr('action')
                    let kb = parseInt(auctionProduct.kb);

                    let nextNominalBid = (kb + currentMaxBid);

                    autoBid = parseInt($('#auto_bid').unmask());

                    if (nextNominalBid > autoBid) {
                        cancelAutoBid()
                        return false;
                    }

                    // console.log({statusAutoBid, autoBid, nominalBid})
                    if (meMaxBid === true) {
                        console.log('meMaxBid')
                        // statusAutoBid = false;
                        document.getElementById("nominal_bid").disabled = false;
                        document.getElementById("auto_bid").disabled = false;
                        return false;
                    }

                    if (statusAutoBid === false) {
                        console.log('statusAutoBid')
                        document.getElementById("nominal_bid").disabled = false;
                        document.getElementById("auto_bid").disabled = false;
                        return false;
                    }

                    var autoBid = parseInt($('#auto_bid').unmask());

                    formData.append('auto_bid', autoBid)
                    formData.set('nominal_bid', nextNominalBid)

                    bidding(formData, url);
                },

                success: function(res) {
                    meMaxBid = res.meMaxBid;
                    var historyBidHtml = 'Belum ada data bidding';

                    if (res.maxBid !== null) {
                        currentMaxBid = parseInt(res.maxBid)
                    }

                    if (res.logBid !== null) {
                        nominalBid = parseInt(res.logBid.nominal_bid)
                    }

                    if (res.logBids !== null) {
                        historyBidHtml =
                            `<table class="table table-dark table-hover"><thead><tr><th scope="col" class="text-danger">Nama</th><th scope="col" class="text-danger">Nominal Bidding</th><th scope="col" class="text-danger">Waktu</th></tr></thead><tbody>`

                        $.each(res.logBids, function(index, value) {
                            var name = value.log_bid.member.nama
                            name = name.replace(/(.{2})(.+)(.{1})/g, function(match, start, middle,
                                end) {
                                return start + "*".repeat(middle.length) + end;
                            });

                            var nominal = thousandSeparator(value.nominal_bid)

                            historyBidHtml += `
                            <tr>
                                <td>${name}</td>
                                <td>${currency} ${nominal}</td>
                                <td>${value.bid_time}</td>
                            </tr>
                           `
                        });
                        historyBidHtml += `</tbody></table>`
                    }

                    $('#exampleModal .modal-body').html(historyBidHtml);

                    var currentPriceHtml = $('#currentPrice').html();
                    var formatedMaxBid = thousandSeparator(res.maxBid)
                    $('#currentPrice').html(`${formatedMaxBid}`);

                    if (currentPriceHtml !== `${formatedMaxBid}`) {
                        addedExtraTime = res.addedExtraTime;
                        document.getElementById("currentPrice").style.display = 'none'
                        $('#currentPrice').slideDown();
                    }
                },
                error(err) {

                }
            })
        }

        function startTimer() {
            var endTime = new Date(currentEndTime.replace(' ', 'T'));
            var x = setInterval(function() {
                getCurrentNow();
                var now = new Date(currentTime.replace(' ', 'T'));
                var duration = endTime - now;
                var days = Math.floor(duration / (1000 * 60 * 60 * 24));
                var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                hours = hours + (days * 24);
                var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((duration % (1000 * 60)) / 1000);
                const hourString = `${hours < 10 ? '0' : ''}${hours}`;
                const minuteString = `${minutes < 10 ? '0' : ''}${minutes}`;
                const secondString = `${seconds < 10 ? '0' : ''}${seconds}`;
                const timerString = `${hourString} : ${minuteString} : ${secondString}`;
                $('.countdown-label').html(timerString);
                if (duration < 0) {
                    $('.countdown-label').html(`00 : 00 : 00`);

                    clearInterval(x);
                    startExtraTimer();
                }
            }, 1000);
        }

        function startExtraTimer() {
            var currTime = moment()
            var x = setInterval(function() {
                getCurrentNow();
                var endTime = new Date(addedExtraTime.replace(' ', 'T'));
                var now = new Date(currentTime.replace(' ', 'T'));
                var duration = endTime - now;
                var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((duration % (1000 * 60)) / 1000);

                const hourString = `${hours < 10 ? '0' : ''}${hours}`;
                const minuteString = `${minutes < 10 ? '0' : ''}${minutes}`;
                const secondString = `${seconds < 10 ? '0' : ''}${seconds}`;
                const timerString = `${hourString} : ${minuteString} : ${secondString}`;
                $('.countdown-label').html(timerString);
                $('#countdown-extra').removeClass('d-none');
                if (duration < 0) {
                    $('.countdown-label').html(`00 : 00 : 00`);

                    document.getElementById("nominal_bid").disabled = true;
                    document.getElementById("auto_bid").disabled = true;
                    document.getElementById("buttonAutoBid").disabled = true;
                    document.getElementById("buttonNormalBid").disabled = true;
                    clearInterval(x);
                }
            }, 1000);
        }

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

        startTimer();
        autoDetailBid();
    </script>
@endpush
