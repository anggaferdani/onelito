@extends('admin.layouts.app')

@section('title', 'Admin')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet"
        href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet"
        href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css"> --}}

    <link rel="stylesheet"
        href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
@endpush

@section('main')
@if (session('success'))
    <div class="alert alert-success">
        <strong>Berhasil !</strong>
        <p>{{ session('success') }}</p>
    </div>
@elseif (session('error'))
    <div class="alert alert-danger">
        <strong>Gagal !</strong>
        <p>{{ session('error') }}</p>
    </div>
@endif
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Pemenang Lelang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('bot-pemenang-lelang.index') }}">Data Pemenang Lelang</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-bordered dtable" id="table">
                            <thead>
                                <tr>
                                    <th width=20>No.</th>
                                    <th>Nama Pemenang</th>
                                    <th>Nomor Ikan</th>
                                    <th>Alamat Tinggal</th>
                                    <th>Kota Tinggal</th>
                                    <th>Harga Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->full_name }}</td>
                                        <td>{{ $item->nomor_ikan }}</td>
                                        <td>{{ $item->alamat_tinggal }}</td>
                                        <td>{{ $item->kota_tinggal }}</td>
                                        <td>Rp. {{ number_format($item->harga_akhir) }}</td>
                                        <td>
                                            <a href="{{ route('export', $item->id) }}"
                                                class="btn btn-sm btn-info">Invoice</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
    <!-- JS Libraies -->
    {{-- <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script> --}}
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <!-- <script src="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.js') }}"></script> -->
    {{-- <script src="{{ asset() }}"></script> --}}
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <script src="https://demo.getstisla.com/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/admin-admin.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                buttons: [
                    'pageLength', 'csv', 'pdf', 'print',
                    {
                        extend: 'excelHtml5',
                        title: 'Data Pemenang Lelang - ' + Date.now(),
                        autoFilter: true,
                        sheetName: 'Data Pemenang Lelang - ' + Date.now(),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7],
                            modifier: {
                                page: 'all',
                                search: 'applied',
                                order: 'applied',
                            }
                        },
                    }
                ]
            });
        });
    </script>


@endpush
