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
                        <div class="col-12 col-md-8">
                            <form method="" id="" action="" class="row g-0">
                                @csrf
                                <div class="col-9">
                                    <input type="text" id="" name="" value=""
                                        class="d-none form-control number-separator">
                                </div>
                                <div class="col-3">
                                    <button id="buttonHistoryBidding" type="button"
                                        class="btn btn-secondary w-100" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">HISTORY</button>
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
                            <div class="col-12 col-md-8">
                                <form method="POST" id="normalBidForm" action="/auction/{{ $idIkan }}" class="row g-0">
                                    @csrf
                                    <div class="col-9">
                                        <input type="text" 
                                            id="nominal_bid" 
                                            name="nominal_bid" 
                                            value="" 
                                            required
                                            class="form-control number-separator" 
                                            placeholder="Nominal BID"
                                            style="border-top-right-radius: 0; border-bottom-right-radius: 0;"
                                            {{ $disableManualBid ?? false ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-3">
                                        <button id="buttonNormalBidSubmit" type="submit" hidden class="d-none"></button>
                                        <button id="buttonNormalBid" 
                                            type="button" 
                                            onclick="clickyakin()"
                                            class="btn btn-danger w-100 h-100"
                                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                            {{ $disableManualBid ?? false ? 'disabled' : '' }}>
                                            BID
                                        </button>
                                    </div>
                                </form>
                                
                                @if($disableManualBid ?? false)
                                <div class="alert alert-info small mt-2 mb-0 manual-bid-disabled">
                                    Bid manual dinonaktifkan karena Auto Bid Anda masih aktif. 
                                    Bid manual akan otomatis aktif kembali jika harga melebihi limit Auto Bid Anda 
                                    (<strong class="auto-bid-limit-value">{{ $auctionProduct->currency->symbol }} {{ number_format($autoBid, 0, '.', '.') }}</strong>).
                                </div>
                                @endif
                                
                                <div class="alert alert-danger bid alert-dismissible fade mb-0 mt-3" role="alert">
                                </div>
                            </div>
                        @endauth
                    </div>

                    <div class="row m-1">
                        <div class="col-md-4">
                        </div>
                        @auth('member')
                            <div class="col-12 col-md-8">
                                <div class="alert alert-secondary small">
                                    Fitur Auto Bid kini tersedia untuk membantu Anda mengikuti lelang secara otomatis.
                                    Sebelum menggunakan fitur ini, pahami cara kerjanya agar lelang berjalan lancar.
                                    <a href="javascript:void(0)" id="autoBidInfo" class="text-danger">Lihat panduan Auto Bid</a>.
                                    Jika membutuhkan bantuan, silakan hubungi
                                    <a href="https://wa.me/6282124425038" target="_blank" class="text-danger">Customer Support Onelito</a>.
                                </div>
                                <form method="POST" id="autoBidForm" action="/auction/{{ $idIkan }}" class="row g-0">
                                    <div class="col-9">
                                        <input type="text" id="auto_bid" name="auto_bid" class="form-control"
                                            value="" placeholder="Nominal Max Auto BID"
                                            style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                    </div>
                                    <div class="col-3">
                                        <button type="submit" id="buttonAutoBid"
                                            class="btn btn-primary w-100 h-100"
                                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                            AUTO
                                        </button>
                                    </div>
                                </form>

                                <div id="autoBidActiveBox" class="mt-2 {{ $autoBid > 0 ? '' : 'd-none' }}">
                                    <div class="row g-0">
                                        <div class="col-9">
                                            <input type="text"
                                                id="auto_bid_active"
                                                class="form-control number-separator border border-danger text-danger"
                                                readonly
                                                style="border-top-right-radius: 0; border-bottom-right-radius: 0;"
                                                value="{{ $autoBid > 0 ? number_format($autoBid, 0, '.', '.') : '' }}">
                                        </div>
                                        <div class="col-3">
                                            <button type="button"
                                                id="buttonCancelAutoBid"
                                                onclick="cancelAutoBid()"
                                                class="btn btn-danger w-100 h-100"
                                                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                CANCEL
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
            // ✅ BARU: Cek jika button disabled
            if ($('#buttonNormalBid').prop('disabled')) {
                Swal.fire('Error', 'Bid manual dinonaktifkan karena Auto Bid masih aktif.', 'error');
                return;
            }
            
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
            
            // ✅ BARU: Cek jika input disabled
            if ($('#nominal_bid').prop('disabled')) {
                Swal.fire('Error', 'Bid manual dinonaktifkan. Silakan batalkan Auto Bid terlebih dahulu atau tunggu hingga harga melebihi limit Auto Bid Anda.', 'error');
                return;
            }
            
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

                // ✅ BARU: Handle disable/enable manual bid
                let shouldDisableManualBid = false;
                
                if (myAutoBidValue && myAutoBidValue > 0) {
                    myAutoBidOnLoad = myAutoBidValue;
                    $('#autoBidActiveBox').removeClass('d-none');
                    $('#auto_bid_active').val(thousandSeparator(myAutoBidValue));
                    $('#buttonAutoBid').text('UPDATE AUTO BID');
                    
                    // ✅ Disable manual bid jika currentPrice masih di bawah auto bid limit
                    shouldDisableManualBid = currentMaxBid < myAutoBidValue;
                } else {
                    $('#autoBidActiveBox').addClass('d-none');
                    $('#auto_bid_active').val('');
                    $('#buttonAutoBid').text('AUTO BID');
                    shouldDisableManualBid = false;
                }

                // ✅ FIXED: Update status disable/enable input dan button
                if (shouldDisableManualBid) {
                    $('#nominal_bid').prop('disabled', true);
                    $('#buttonNormalBid').prop('disabled', true);
                    
                    // Cek apakah alert sudah ada (dari blade atau ajax sebelumnya)
                    const existingAlert = $('.alert-info.manual-bid-disabled');
                    
                    if (existingAlert.length === 0) {
                        // Buat alert baru jika belum ada
                        const alertHtml = `
                            <div class="alert alert-info small mt-2 mb-0 manual-bid-disabled">
                                Bid manual dinonaktifkan karena Auto Bid Anda masih aktif. 
                                Bid manual akan otomatis aktif kembali jika harga melebihi limit Auto Bid Anda 
                                (<strong class="auto-bid-limit-value">${currency} ${thousandSeparator(myAutoBidValue)}</strong>).
                            </div>
                        `;
                        
                        // Insert tepat setelah form normalBidForm, sebelum alert.bid
                        $('#normalBidForm').after(alertHtml);
                    } else {
                        // Update hanya nilai auto bid limit di alert yang sudah ada
                        existingAlert.find('.auto-bid-limit-value').text(`${currency} ${thousandSeparator(myAutoBidValue)}`);
                        
                        // ✅ IMPORTANT: Pastikan alert visible (tidak hidden)
                        existingAlert.show();
                    }
                } else {
                    $('#nominal_bid').prop('disabled', false);
                    $('#buttonNormalBid').prop('disabled', false);
                    
                    // Hapus alert info jika ada
                    $('.alert-info.manual-bid-disabled').remove();
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

        // Update function cancelAutoBid untuk re-enable manual bid
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
                            
                            // ✅ BARU: Enable manual bid setelah cancel auto bid
                            $('#nominal_bid').prop('disabled', false);
                            $('#buttonNormalBid').prop('disabled', false);
                            $('.alert-info.manual-bid-disabled').remove();
                        }

                        swalWithBootstrapButtons.fire(
                            'Berhasil!',
                            'Auto Bid berhasil dibatalkan. Bid manual sekarang aktif kembali.',
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
                    // ✅ Disable semua input ketika lelang berakhir
                    $("#nominal_bid, #buttonNormalBid, #auto_bid, #buttonAutoBid").prop('disabled', true);
                    clearInterval(timerInterval);
                    timerInterval = null;
                } else {
                    // ✅ Hanya enable auto bid, manual bid tergantung status auto bid
                    $("#auto_bid, #buttonAutoBid").prop('disabled', false);
                    
                    // Manual bid tetap cek status auto bid
                    // (sudah dihandle di autoDetailBid)
                    
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
                    <ol style="text-align:left; padding-left: 20px;">
                        <li><span style="color:red;">Auto Bid</span> akan secara otomatis menaikkan bid Anda hingga mencapai nominal maksimum yang ditentukan.</li>
                        <li><span style="color:red;">Auto Bid</span> hanya bisa digunakan jika nominal lebih besar dari harga saat ini.</li>
                        <li>Nominal <span style="color:red;">Auto Bid</span> harus sesuai dengan kelipatan BID yang berlaku pada lelang ini.</li>
                        <li>Sistem akan langsung menaikkan bid Anda saat peserta lain memasang bid (manual atau auto).</li>
                        <li>Jika ada peserta lain dengan <span style="color:red;">Auto Bid</span> lebih tinggi, <span style="color:red;">Auto Bid</span> Anda akan berhenti pada nominal maksimum Anda.</li>
                        <li>Jika nominal <span style="color:red;">Auto Bid</span> Anda sama dengan peserta lain, yang memasang <span style="color:red;">Auto Bid</span> lebih daholu akan menjadi pemenang.</li>
                        <li>Manual Bid dapat memicu <span style="color:red;">Auto Bid</span> peserta lain untuk aktif secara otomatis.</li>
                        <li>Anda bisa meng-update nominal <span style="color:red;">Auto Bid</span> kapan saja, baik untuk menaikkan atau menurunkan limit maksimum.</li>
                        <li>Jika Anda sedang menjadi top bidder, meng-update <span style="color:red;">Auto Bid</span> tidak akan memicu kenaikan harga kecuali ada kompetitor dengan <span style="color:red;">auto bid</span> lebih tinggi atau sama.</li>
                        <li>Anda bisa membatalkan <span style="color:red;">Auto Bid</span> kapan saja dengan menekan tombol CANCEL <span style="color:red;">AUTO BID</span>.</li>
                        <li>Setelah <span style="color:red;">Auto Bid</span> diaktifkan, tombol <span style="color:red;">Auto Bid</span> akan berubah menjadi UPDATE <span style="color:red;">AUTO BID</span>.</li>
                        <li>Sistem <span style="color:red;">Auto Bid</span> akan terus berjalan hingga mencapai limit maksimum Anda atau tidak ada peserta lain yang bersaing.</li>
                        <li>Pastikan nominal sudah benar sebelum submit!</li>
                        <li>Jika Anda membutuhkan bantuan lebih lanjut, silakan hubungi <a href="https://wa.me/6282124425038" target="_blank" style="color:red;">Customer Support Onelito</a>.</li>
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
