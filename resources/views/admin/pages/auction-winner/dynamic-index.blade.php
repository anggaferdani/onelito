@extends('admin.layouts.app')

@section('title', 'Pemenang Lelang (Live)')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pemenang Lelang (Live)</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Pemenang Lelang Live</div>
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
                ]
            });
        });
    </script>
@endpush
