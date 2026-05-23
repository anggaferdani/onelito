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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pemenang &mdash; <span id="detail-fish-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Info Ikan --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Event</small>
                            <strong id="detail-event-name"></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Selesai</small>
                            <strong id="detail-tgl-akhir"></strong>
                        </div>
                    </div>

                    <hr>

                    {{-- Detail Pemenang --}}
                    <h6 class="font-weight-bold mb-3">Pemenang</h6>
                    <div id="detail-winner-section">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" width="120">Nama</td>
                                        <td>: <strong id="detail-winner-nama"></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. HP</td>
                                        <td>: <span id="detail-winner-hp"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td>: <span id="detail-winner-email"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" width="120">Kota</td>
                                        <td>: <span id="detail-winner-kota"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nominal Bid</td>
                                        <td>: <strong class="text-success" id="detail-winner-nominal"></strong></td>
                                    </tr>
                                </table>
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
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="detail-history-body">
                            </tbody>
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
                    { data: 'event_name', orderable: false },
                    { data: 'tgl_akhir', orderable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                ]
            });

            $(document).on('click', '.btn-detail', function () {
                var idIkan = $(this).data('id');

                $('#detail-fish-name').text('');
                $('#detail-event-name').text('');
                $('#detail-tgl-akhir').text('');
                $('#detail-winner-nama').text('');
                $('#detail-winner-hp').text('');
                $('#detail-winner-email').text('');
                $('#detail-winner-kota').text('');
                $('#detail-winner-nominal').text('');
                $('#detail-history-body').html('<tr><td colspan="6" class="text-center text-muted">Memuat...</td></tr>');
                $('#detail-winner-section').removeClass('d-none');
                $('#detail-no-winner').addClass('d-none');

                $('#modalDetail').modal('show');

                $.get('{{ url("admin/dynamic-winners") }}/' + idIkan + '/detail', function (res) {
                    $('#detail-fish-name').text(res.fish.no_ikan + ' — ' + res.fish.variety);
                    $('#detail-event-name').text(res.fish.event_name);
                    $('#detail-tgl-akhir').text(res.fish.tgl_akhir);

                    if (res.winner) {
                        $('#detail-winner-section').removeClass('d-none');
                        $('#detail-no-winner').addClass('d-none');
                        $('#detail-winner-nama').text(res.winner.nama);
                        $('#detail-winner-hp').text(res.winner.no_hp);
                        $('#detail-winner-email').text(res.winner.email);
                        $('#detail-winner-kota').text(res.winner.kota);
                        $('#detail-winner-nominal').text(res.winner.nominal);
                    } else {
                        $('#detail-winner-section').addClass('d-none');
                        $('#detail-no-winner').removeClass('d-none');
                    }

                    var rows = '';
                    if (res.history.length === 0) {
                        rows = '<tr><td colspan="6" class="text-center text-muted">Belum ada history bidding.</td></tr>';
                    } else {
                        $.each(res.history, function (i, bid) {
                            var badge = bid.is_winner
                                ? '<span class="badge badge-success">Pemenang</span>'
                                : '';
                            rows += '<tr' + (bid.is_winner ? ' class="table-success"' : '') + '>'
                                + '<td>' + (i + 1) + '</td>'
                                + '<td>' + bid.nama + '</td>'
                                + '<td>' + bid.no_hp + '</td>'
                                + '<td><strong>' + bid.nominal_bid + '</strong></td>'
                                + '<td>' + bid.waktu_bid + '</td>'
                                + '<td>' + badge + '</td>'
                                + '</tr>';
                        });
                    }
                    $('#detail-history-body').html(rows);
                });
            });
        });
    </script>
@endpush
