@extends('admin.layouts.app')

@section('title', 'New Pemenang Auction per User')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>New Pemenang Auction per User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">New Pemenang Auction per User</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <small class="text-muted">Pemenang per user per event — satu user bisa muncul lebih dari satu kali jika menang di event berbeda.</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-per-user">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>No. HP</th>
                                                <th>Event</th>
                                                <th>Tanggal Event</th>
                                                <th>Jumlah Ikan Menang</th>
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
    <div class="modal fade" id="modalDetailUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pemenang &mdash; <span id="du-nama"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Info Member --}}
                    <h6 class="font-weight-bold mb-3">Data Member</h6>
                    <div class="row mb-3">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <img id="du-profile-pic" src="" alt="Foto"
                                class="img-fluid rounded-circle shadow-sm"
                                style="width:80px;height:80px;object-fit:cover;">
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td class="text-muted" width="100">Nama</td><td>: <strong id="du-nama-detail"></strong></td></tr>
                                        <tr><td class="text-muted">No. HP</td><td>: <span id="du-hp"></span></td></tr>
                                        <tr><td class="text-muted">Email</td><td>: <span id="du-email"></span></td></tr>
                                        <tr><td class="text-muted">Alamat</td><td>: <span id="du-alamat"></span></td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td class="text-muted" width="100">Kelurahan</td><td>: <span id="du-kelurahan"></span></td></tr>
                                        <tr><td class="text-muted">Kecamatan</td><td>: <span id="du-kecamatan"></span></td></tr>
                                        <tr><td class="text-muted">Kota</td><td>: <span id="du-kota"></span></td></tr>
                                        <tr><td class="text-muted">Provinsi</td><td>: <span id="du-provinsi"></span></td></tr>
                                        <tr><td class="text-muted">Kode Pos</td><td>: <span id="du-kodepos"></span></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Info Event --}}
                    <h6 class="font-weight-bold mb-2">Event</h6>
                    <p class="mb-1"><strong id="du-event-name"></strong></p>
                    <p class="text-muted mb-3"><span id="du-event-tgl"></span></p>

                    <hr>

                    {{-- Daftar Ikan yang Dimenangkan --}}
                    <h6 class="font-weight-bold mb-3">Ikan yang Dimenangkan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>#</th>
                                    <th>No. Ikan</th>
                                    <th>Variety</th>
                                    <th>Nominal Bid</th>
                                    <th>Tipe Bid</th>
                                    <th>Waktu Bid</th>
                                </tr>
                            </thead>
                            <tbody id="du-fish-body"></tbody>
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
            $('#table-per-user').DataTable({
                responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url("admin/winner-per-user") }}',
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama' },
                    { data: 'no_hp' },
                    { data: 'event_name' },
                    { data: 'tgl_event', orderable: false },
                    { data: 'jumlah_ikan', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'aksi', orderable: false, searchable: false },
                ]
            });

            $(document).on('click', '.btn-detail-user', function () {
                var idPeserta = $(this).data('peserta');
                var idEvent   = $(this).data('event');

                var noPhoto  = 'https://via.placeholder.com/60?text=No+Foto';
                var noAvatar = 'https://via.placeholder.com/80?text=?';
                $('#du-nama').text('');
                $('#du-profile-pic').attr('src', noAvatar);
                $('#du-nama-detail').text('');
                $('#du-hp').text('');
                $('#du-email').text('');
                $('#du-alamat').text('');
                $('#du-kelurahan').text('');
                $('#du-kecamatan').text('');
                $('#du-kota').text('');
                $('#du-provinsi').text('');
                $('#du-kodepos').text('');
                $('#du-event-name').text('');
                $('#du-event-tgl').text('');
                $('#du-fish-body').html('<tr><td colspan="7" class="text-center text-muted">Memuat...</td></tr>');

                $('#modalDetailUser').modal('show');

                $.get('{{ url("admin/winner-per-user") }}/' + idPeserta + '/' + idEvent + '/detail', function (res) {
                    $('#du-nama').text(res.member.nama);
                    $('#du-profile-pic').attr('src', res.member.profile_pic || noAvatar);
                    $('#du-nama-detail').text(res.member.nama);
                    $('#du-hp').text(res.member.no_hp);
                    $('#du-email').text(res.member.email);
                    $('#du-alamat').text(res.member.alamat);
                    $('#du-kelurahan').text(res.member.kelurahan);
                    $('#du-kecamatan').text(res.member.kecamatan);
                    $('#du-kota').text(res.member.kota);
                    $('#du-provinsi').text(res.member.provinsi);
                    $('#du-kodepos').text(res.member.kode_pos);
                    $('#du-event-name').text(res.event.name);
                    $('#du-event-tgl').text(res.event.tgl_mulai + ' — ' + res.event.tgl_akhir);

                    var rows = '';
                    if (res.fishes.length === 0) {
                        rows = '<tr><td colspan="7" class="text-center text-muted">Tidak ada ikan.</td></tr>';
                    } else {
                        $.each(res.fishes, function (i, fish) {
                            var tipeBadge = fish.is_auto ? '<span class="badge badge-danger">Auto Bid</span>' : '';
                            var photoHtml = '<img src="' + (fish.photo_url || noPhoto) + '" style="max-width:60px;height:auto;border-radius:4px;">';
                            rows += '<tr>'
                                + '<td>' + photoHtml + '</td>'
                                + '<td>' + (i + 1) + '</td>'
                                + '<td>' + fish.no_ikan + '</td>'
                                + '<td>' + fish.variety + '</td>'
                                + '<td><strong>' + fish.nominal_bid + '</strong></td>'
                                + '<td>' + tipeBadge + '</td>'
                                + '<td>' + fish.waktu_bid + '</td>'
                                + '</tr>';
                        });
                    }
                    $('#du-fish-body').html(rows);
                });
            });
        });
    </script>
@endpush
