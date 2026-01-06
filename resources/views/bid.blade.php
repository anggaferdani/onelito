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
    <br><br><br><br><br><br>
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
                            $imgUrl = 'img/koi11.webp';

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
                                        <td>: {{ $auctionProduct->variety }}</td>
                                    </tr>
                                    <tr>
                                        <td>Breeder</td>
                                        <td>: {{ $auctionProduct->breeder }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bloodline</td>
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
                                        <td>: {{ $auctionProduct->sex }}</td>
                                    </tr>
                                    <tr>
                                        <td>DOB</td>
                                        <td>: {{ $auctionProduct->dob }}</td>
                                    </tr>
                                    <tr>
                                        <td>Size</td>
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
                    @guest('member')
                        <div class="col-12 col-md-8">
                            <div class="alert alert-danger">
                                Silakan <a href="{{ route('login') }}" class="alert-link">login</a> untuk ikut bidding dan melihat history.
                            </div>
                        </div>
                    @endguest
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
                                        class="btn btn-secondary w-100 justify-content-between" data-bs-toggle="modal"
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
                                <div class="alert alert-secondary small">Sebelum menggunakan fitur Auto Bid, pahami cara kerjanya agar lelang berjalan lancar. <a href="javascript:void(0)" id="autoBidInfo" class="text-danger">Lihat panduan Auto Bid.</a></div>
                                <form method="POST" id="autoBidForm" action="/auction/{{ $idIkan }}" class="row">
                                    <div class="col-7 col-md-9" style="padding-right:0px">
                                        <input type="text" id="auto_bid" name="auto_bid" class="form-control"
                                            value="" id="exampleFormControlInput1" placeholder="Nominal Max Auto BID">
                                    </div>
                                    <div class="col-5 col-md-3" style="padding-left:0px">
                                        <button type="submit" id="buttonAutoBid"
                                            class="btn btn-primary w-100 justify-content-between">
                                            AUTO BID
                                        </button>
                                    </div>
                                </form>

                                <div id="autoBidActiveBox" class="mt-2 {{ $autoBid > 0 ? '' : 'd-none' }}">
                                    <div class="row">
                                        <div class="col-7 col-md-9" style="padding-right:0px">
                                            <input type="text"
                                                id="auto_bid_active"
                                                class="form-control number-separator border border-danger text-danger"
                                                readonly
                                                value="{{ $autoBid > 0 ? number_format($autoBid, 0, '.', '.') : '' }}">
                                        </div>
                                        <div class="col-5 col-md-3" style="padding-left:0px">
                                            <button type="button"
                                                id="buttonCancelAutoBid"
                                                onclick="cancelAutoBid()"
                                                class="btn btn-danger w-100">
                                                CANCEL AUTO BID
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        let currentMaxBid = @json($maxBid);
        let myAutoBidOnLoad = @json($autoBid);
        let idIkan = @json($idIkan);
        let auctionProduct = @json($auctionProduct);
        let currency = auctionProduct.currency.symbol;
        let meMaxBid = false;

        let regularEndTime = moment(@json($auctionProduct->event->tgl_akhir));
        let extraEndTime = moment(@json($addedExtraTime));
        
        let isExtraTimeActive = false;
        let timerInterval;

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        function clickyakin() {
            var nominal = $('#nominal_bid').val();
            if (!nominal || parseInt($('#nominal_bid').unmask()) <= 0) {
                Swal.fire('Error', 'Nominal bid tidak boleh kosong.', 'error');
                return;
            }

            swalWithBootstrapButtons.fire({
                title: `Apakah anda yakin ingin Bidding ${currency} ${nominal} ?`,
                text: `Pastikan nominal Anda sudah benar.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lakukan Bid!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#buttonNormalBidSubmit").click();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Dibatalkan',
                        'Bidding Anda telah dibatalkan.',
                        'error'
                    );
                }
            });
        }

        function thousandSeparator(x) {
            if (x === null || x === undefined) return '0';
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $('#nominal_bid').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });

        $('#auto_bid').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });

        $('#auto_bid_active').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });

        $('#normalBidForm').submit(function(e) {
            e.preventDefault();
            $.LoadingOverlay("show");
            let formData = new FormData(this);
            let url = $(this).attr('action');
            var inputNominalBid = parseInt($('#nominal_bid').unmask());

            formData.set('nominal_bid', inputNominalBid);
            formData.append('nominal_bid_detail', inputNominalBid);
            
            bidding(formData, url);
        });

        $('#autoBidForm').submit(function(e) {
            e.preventDefault();
            let autoBid = parseInt($('#auto_bid').unmask());

            if (autoBid <= currentMaxBid) {
                Swal.fire('Error', 'Auto Bid harus lebih besar dari harga saat ini', 'error');
                return;
            }

            if (!autoBid || autoBid <= 0) {
                Swal.fire('Error', 'Nominal Auto Bid tidak valid.', 'error');
                return;
            }

            swalWithBootstrapButtons.fire({
                title: `Apakah anda yakin ingin Auto Bid hingga ${currency} ${thousandSeparator(autoBid)}?`,
                text: `Auto Bid akan otomatis menaikkan bid Anda hingga nominal ini.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan Auto Bid!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.LoadingOverlay("show");
                let formData = new FormData(this);
                formData.set('auto_bid', autoBid);
                formData.append('_token', '{{ csrf_token() }}');
                bidding(formData, $(this).attr('action'));
            });
        });

        async function bidding(formData, url) {
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                url: url,
                complete: function() {
                    $.LoadingOverlay("hide");
                },
                success: function(res) {
                    $('#nominal_bid').val('');
                    $('#auto_bid').val('');
                    swalWithBootstrapButtons.fire('Berhasil!', 'Bidding Anda telah diterima.', 'success');
                    autoDetailBid();
                },
                error: function(err) {
                    const message = err.responseJSON?.message || 'Terjadi kesalahan, coba lagi.';
                    $('.alert.bid').html(message).addClass('show');
                    setTimeout(() => $('.alert.bid').removeClass('show'), 3000);
                }
            });
        }

        function autoDetailBid() {
            let urlGet = `/auction/${idIkan}/detail`;

            $.get(urlGet, function(res) {
                meMaxBid = res.meMaxBid;
                currentMaxBid = parseInt(res.maxBid);
                $('#currentPrice').text(thousandSeparator(res.maxBid));

                var historyBidHtml = 'Belum ada data bidding.';
                if (res.logBids && res.logBids.length > 0) {
                    historyBidHtml = `<table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-danger">Nama</th>
                                <th scope="col" class="text-danger">Nominal Bidding</th>
                                <th scope="col" class="text-danger">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    $.each(res.logBids, function(index, value) {
                        var name = value.log_bid.member.nama.replace(/(.{2})(.+)(.{1})/g, (match, start, middle, end) => start + "*".repeat(middle.length) + end);
                        
                        var nominal = thousandSeparator(value.nominal_bid);
                        
                        var autoBidBadge = value.status_bid === 1 ? '<span class="badge bg-danger ms-1">AUTO BID</span>' : '';
                        
                        historyBidHtml += `<tr>
                            <td>${name}</td>
                            <td>${currency} ${nominal} ${autoBidBadge}</td>
                            <td>${value.bid_time}</td>
                        </tr>`;
                    });

                    historyBidHtml += `</tbody></table>`;
                }

                $('#exampleModal .modal-body').html(historyBidHtml);

                let myAutoBidValue = res.autoBid ?? myAutoBidOnLoad;

                if (myAutoBidValue && myAutoBidValue > 0) {
                    myAutoBidOnLoad = myAutoBidValue;
                    $('#autoBidActiveBox').removeClass('d-none');
                    $('#auto_bid_active').val(thousandSeparator(myAutoBidValue));
                    $('#buttonAutoBid').text('UPDATE AUTO BID');
                } else {
                    $('#autoBidActiveBox').addClass('d-none');
                    $('#auto_bid_active').val('');
                    $('#buttonAutoBid').text('AUTO BID');
                }

                const newExtraTime = moment(res.addedExtraTime);
                if (newExtraTime.isAfter(extraEndTime)) {
                    console.log("Waktu lelang diperpanjang!");
                    extraEndTime = newExtraTime;
                    if (!timerInterval) {
                        startTimer();
                    }
                }
            }).fail(function(err) {
                console.error("Gagal mengambil detail lelang:", err);
            });
        }

        function cancelAutoBid() {
            swalWithBootstrapButtons.fire({
                title: 'Batalkan Auto Bid?',
                text: 'Auto Bid Anda akan dihentikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.LoadingOverlay("show");

                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('auto_bid', 0);

                $.ajax({
                    type: 'POST',
                    url: `/auction/${idIkan}`,
                    data: formData,
                    contentType: false,
                    processData: false,
                    complete: function () {
                        $.LoadingOverlay("hide");
                    },
                    success: function(res) {
                        $('#nominal_bid').val('');
                        $('#auto_bid').val('');

                        if (res.autoBid && res.autoBid > 0) {
                            myAutoBidOnLoad = res.autoBid;
                            $('#autoBidActiveBox').removeClass('d-none');
                            $('#auto_bid_active').val(thousandSeparator(res.autoBid));
                            $('#buttonAutoBid').text('UPDATE AUTO BID');
                        } else {
                            myAutoBidOnLoad = 0;
                            $('#autoBidActiveBox').addClass('d-none');
                            $('#auto_bid_active').val('');
                            $('#buttonAutoBid').text('AUTO BID');
                        }

                        swalWithBootstrapButtons.fire(
                            'Berhasil!',
                            'Auto Bid berhasil dibatalkan.',
                            'success'
                        );

                        autoDetailBid();
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal membatalkan Auto Bid', 'error');
                    }
                });
            });
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);

            timerInterval = setInterval(function() {
                let now = moment();
                
                if (!isExtraTimeActive && moment.duration(regularEndTime.diff(now)) <= 0) {
                    isExtraTimeActive = true;
                    $('#countdown-extra').removeClass('d-none');
                }

                let targetTime = isExtraTimeActive ? extraEndTime : regularEndTime;
                let duration = moment.duration(targetTime.diff(now));
                
                if (duration <= 0) {
                    $('.countdown-label').html(`00 : 00 : 00`);
                    $("#nominal_bid, #buttonNormalBid").prop('disabled', true);
                    clearInterval(timerInterval);
                    timerInterval = null;
                } else {
                    $("#nominal_bid, #buttonNormalBid").prop('disabled', false); 
                    
                    const hours = Math.floor(duration.asHours());
                    const minutes = duration.minutes();
                    const seconds = duration.seconds();

                    const timerString = `${String(hours).padStart(2, '0')} : ${String(minutes).padStart(2, '0')} : ${String(seconds).padStart(2, '0')}`;
                    $('.countdown-label').html(timerString);
                }
            }, 1000);
        }

        $('#autoBidInfo').click(function() {
            Swal.fire({
                title: 'Panduan Auto Bid.',
                html: `
                    <ul style="text-align:left; padding-left: 20px;">
                        <li>Auto Bid akan secara otomatis menaikkan bid Anda hingga mencapai nominal maksimum yang ditentukan.</li>
                        <li>Auto Bid hanya bisa digunakan jika nominal lebih besar dari harga saat ini.</li>
                        <li>Nominal Auto Bid harus sesuai dengan kelipatan BID yang berlaku pada lelang ini</li>
                        <li>Jika ada peserta lain yang menawar lebih tinggi, Auto Bid Anda akan berhenti pada nominal maksimum Anda.</li>
                        <li>Anda bisa membatalkan Auto Bid kapan saja dengan tombol CANCEL AUTO BID.</li>
                        <li>Setelah Auto Bid diaktifkan, tombol AUTO BID akan berubah menjadi UPDATE AUTO BID.</li>
                        <li>Pastikan nominal sudah benar sebelum submit!</li>
                    </ul>
                `,
                icon: 'info',
                confirmButtonText: 'Mengerti',
                width: '600px'
            });
        });

        $(document).ready(function() {
            startTimer();

            if (myAutoBidOnLoad && myAutoBidOnLoad > 0) {
                $('#autoBidActiveBox').removeClass('d-none');
                $('#auto_bid_active').val(thousandSeparator(myAutoBidOnLoad));
                $('#buttonAutoBid').text('UPDATE AUTO BID');
            } else {
                $('#autoBidActiveBox').addClass('d-none');
                $('#auto_bid_active').val('');
                $('#buttonAutoBid').text('AUTO BID');
            }

            setInterval(autoDetailBid, 3000);

            $('#buttonHistoryBidding').click(function() {
                $('#exampleModal .modal-body').html(`
                    <div class="text-center my-3">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

                autoDetailBid();
            });
        });
    </script>
@endpush
