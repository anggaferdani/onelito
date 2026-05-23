@extends('admin.layouts.app')

@section('title', 'Pemenang Lelang Live Auction')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pemenang Lelang Live Auction</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Pemenang Live Auction</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <small class="text-muted">Pemenang dihitung langsung dari data bidding — selalu akurat.</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-dynamic">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>No. Ikan</th>
                                                <th>Variety</th>
                                                <th>Pemenang</th>
                                                <th>No. HP</th>
                                                <th>Nominal Bid</th>
                                                <th>Tipe Bid</th>
                                                <th>Event</th>
                                                <th>Tgl. Selesai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pemenang &mdash; <span id="detail-fish-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Detail Ikan --}}
                    <h6 class="font-weight-bold mb-3">Detail Ikan</h6>
                    <div class="row mb-3">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img id="detail-fish-photo" src="" alt="Foto Ikan"
                                class="img-fluid rounded shadow-sm"
                                style="max-height:180px;object-fit:cover;width:100%;">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td class="text-muted" width="100">No. Ikan</td><td>: <strong id="detail-fish-no"></strong></td></tr>
                                        <tr><td class="text-muted">Variety</td><td>: <span id="detail-fish-variety"></span></td></tr>
                                        <tr><td class="text-muted">Breeder</td><td>: <span id="detail-fish-breeder"></span></td></tr>
                                        <tr><td class="text-muted">Bloodline</td><td>: <span id="detail-fish-bloodline"></span></td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td class="text-muted" width="100">Sex</td><td>: <span id="detail-fish-sex"></span></td></tr>
                                        <tr><td class="text-muted">Size</td><td>: <span id="detail-fish-size"></span> cm</td></tr>
                                        <tr><td class="text-muted">Event</td><td>: <span id="detail-event-name"></span></td></tr>
                                        <tr><td class="text-muted">Tgl. Selesai</td><td>: <span id="detail-tgl-akhir"></span></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Detail Pemenang --}}
                    <h6 class="font-weight-bold mb-3">Pemenang</h6>
                    <div id="detail-winner-section">
                        <div class="row">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                <img id="detail-winner-photo" src="" alt="Foto"
                                    class="img-fluid rounded-circle shadow-sm"
                                    style="width:80px;height:80px;object-fit:cover;">
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr><td class="text-muted" width="100">Nama</td><td>: <strong id="detail-winner-nama"></strong></td></tr>
                                            <tr><td class="text-muted">No. HP</td><td>: <span id="detail-winner-hp"></span></td></tr>
                                            <tr><td class="text-muted">Email</td><td>: <span id="detail-winner-email"></span></td></tr>
                                            <tr><td class="text-muted">Nominal</td><td>: <strong class="text-success" id="detail-winner-nominal"></strong> <span id="detail-winner-tipe"></span></td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr><td class="text-muted" width="100">Alamat</td><td>: <span id="detail-winner-alamat"></span></td></tr>
                                            <tr><td class="text-muted">Kelurahan</td><td>: <span id="detail-winner-kelurahan"></span></td></tr>
                                            <tr><td class="text-muted">Kecamatan</td><td>: <span id="detail-winner-kecamatan"></span></td></tr>
                                            <tr><td class="text-muted">Kota</td><td>: <span id="detail-winner-kota"></span></td></tr>
                                            <tr><td class="text-muted">Provinsi</td><td>: <span id="detail-winner-provinsi"></span></td></tr>
                                            <tr><td class="text-muted">Kode Pos</td><td>: <span id="detail-winner-kodepos"></span></td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="detail-no-winner" class="text-muted d-none">Belum ada pemenang.</div>

                    <hr>

                    {{-- History Bidding --}}
                    <h6 class="font-weight-bold mb-3">History Bidding</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>No. HP</th>
                                    <th>Nominal</th>
                                    <th>Waktu Bid</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody id="detail-history-body"></tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#table-dynamic').DataTable({
                responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url("admin/dynamic-winners") }}',
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'no_ikan' },
                    { data: 'variety' },
                    { data: 'pemenang' },
                    { data: 'no_hp' },
                    { data: 'nominal', orderable: false },
                    { data: 'tipe_bid', orderable: false, searchable: false },
                    { data: 'event_name', orderable: false },
                    { data: 'tgl_akhir', orderable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                ]
            });

            $(document).on('click', '.btn-detail', function () {
                var idIkan = $(this).data('id');

                var noPhoto = 'https://via.placeholder.com/300x200?text=No+Photo';
                var noAvatar = 'https://via.placeholder.com/80?text=?';
                $('#detail-fish-name').text('');
                $('#detail-fish-photo').attr('src', noPhoto);
                $('#detail-fish-no').text('');
                $('#detail-fish-variety').text('');
                $('#detail-fish-breeder').text('');
                $('#detail-fish-bloodline').text('');
                $('#detail-fish-sex').text('');
                $('#detail-fish-size').text('');
                $('#detail-event-name').text('');
                $('#detail-tgl-akhir').text('');
                $('#detail-winner-photo').attr('src', noAvatar);
                $('#detail-winner-nama').text('');
                $('#detail-winner-hp').text('');
                $('#detail-winner-email').text('');
                $('#detail-winner-alamat').text('');
                $('#detail-winner-kelurahan').text('');
                $('#detail-winner-kecamatan').text('');
                $('#detail-winner-kota').text('');
                $('#detail-winner-provinsi').text('');
                $('#detail-winner-kodepos').text('');
                $('#detail-winner-nominal').text('');
                $('#detail-winner-tipe').html('');
                $('#detail-history-body').html('<tr><td colspan="6" class="text-center text-muted">Memuat...</td></tr>');
                $('#detail-winner-section').removeClass('d-none');
                $('#detail-no-winner').addClass('d-none');

                $('#modalDetail').modal('show');

                $.get('{{ url("admin/dynamic-winners") }}/' + idIkan + '/detail', function (res) {
                    $('#detail-fish-name').text(res.fish.no_ikan + ' — ' + res.fish.variety);
                    $('#detail-fish-photo').attr('src', res.fish.photo_url || noPhoto);
                    $('#detail-fish-no').text(res.fish.no_ikan);
                    $('#detail-fish-variety').text(res.fish.variety);
                    $('#detail-fish-breeder').text(res.fish.breeder);
                    $('#detail-fish-bloodline').text(res.fish.bloodline);
                    $('#detail-fish-sex').text(res.fish.sex);
                    $('#detail-fish-size').text(res.fish.size);
                    $('#detail-event-name').text(res.fish.event_name);
                    $('#detail-tgl-akhir').text(res.fish.tgl_akhir);

                    if (res.winner) {
                        $('#detail-winner-section').removeClass('d-none');
                        $('#detail-no-winner').addClass('d-none');
                        $('#detail-winner-photo').attr('src', res.winner.profile_pic || noAvatar);
                        $('#detail-winner-nama').text(res.winner.nama);
                        $('#detail-winner-hp').text(res.winner.no_hp);
                        $('#detail-winner-email').text(res.winner.email);
                        $('#detail-winner-alamat').text(res.winner.alamat);
                        $('#detail-winner-kelurahan').text(res.winner.kelurahan);
                        $('#detail-winner-kecamatan').text(res.winner.kecamatan);
                        $('#detail-winner-kota').text(res.winner.kota);
                        $('#detail-winner-provinsi').text(res.winner.provinsi);
                        $('#detail-winner-kodepos').text(res.winner.kode_pos);
                        $('#detail-winner-nominal').text(res.winner.nominal);
                        $('#detail-winner-tipe').html(res.winner.is_auto ? '<span class="badge badge-danger ml-1">Auto Bid</span>' : '');
                    } else {
                        $('#detail-winner-section').addClass('d-none');
                        $('#detail-no-winner').removeClass('d-none');
                    }

                    var rows = '';
                    if (res.history.length === 0) {
                        rows = '<tr><td colspan="6" class="text-center text-muted">Belum ada history bidding.</td></tr>';
                    } else {
                        $.each(res.history, function (i, bid) {
                            var tipeBadge = bid.is_auto ? '<span class="badge badge-danger">Auto Bid</span>' : '';
                            rows += '<tr' + (bid.is_winner ? ' class="table-success"' : '') + '>'
                                + '<td>' + (i + 1) + '</td>'
                                + '<td>' + bid.nama + '</td>'
                                + '<td>' + bid.no_hp + '</td>'
                                + '<td><strong>' + bid.nominal_bid + '</strong></td>'
                                + '<td>' + bid.waktu_bid + '</td>'
                                + '<td>' + tipeBadge + '</td>'
                                + '</tr>';
                        });
                    }
                    $('#detail-history-body').html(rows);
                });
            });
        });
    </script>
@endpush
