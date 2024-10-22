@extends('admin.layouts.app')

@section('title', 'Barang Store')

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

    <link rel="stylesheet"
        href="{{ asset('library/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Barang Store</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Barang Store</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <button class="btn btn-primary mb-3"
                            data-toggle="modal"
                            data-target="#modalCreate"
                            ><i class="fa fa-plus"></i> Tambah Barang Store</button>

                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Kategori</th>
                                                <th>Merek</th>
                                                <th>Nama</th>
                                                <th>Berat</th>
                                                <th>Harga</th>
                                                <th>Deskripsi</th>
                                                <th>Foto</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('admin.pages.product._create')
        @include('admin.pages.product._show')
        @include('admin.pages.product._edit')
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
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/price-separator.min.js') }}"></script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#harga').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#edit_harga').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#table-1').DataTable({
                // dom: 'Bfrtip',
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                buttons: [
                    'pageLength','csv', 'excel', 'pdf', 'print',
                ],
                "responsive": true,
                "autoWidth" : true,
                "processing" : true,
                "serverSide" : true,
                search: {
                    return: true
                },
                ajax : {
                url : '{{ url("admin/products") }}',
                data : function(d) {
                    // d.jenis_task = $('#filter_jenis_task').val()
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'category', name: 'category.kategori_produk' },
                    { data : 'merek_produk'},
                    { data : 'nama_produk'},
                    { data : 'berat'},
                    { data : 'harga'},
                    { data : 'deskripsi'},
                    { data : 'photo', name: 'photo.path_foto', orderable : false,searchable :false},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });
        });

        $(document).on('click','button#btn-show',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `products/${id}`,
                dataType: "json",
                success: function(res) {
                    $('#modalShow').modal('show');
                    $('#show_category').val(res.category.kategori_produk)
                    $('#show_merek_produk').val(res.merek_produk)
                    $('#show_nama_produk').val(res.nama_produk)
                    $('#show_berat').val(res.berat)
                    $('#show_harga').val(res.harga)
                    $('#show_deskripsi').html(res.deskripsi)
                    $('#show_foto').attr('src', ``)
                    if (res.photo) {
                        $('#show_foto').attr('src', `/storage/${res.photo.path_foto}`)
                    }
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })

        $(document).on('click','button#btn-edit',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `products/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `products/${id}`;

                    $('#edit_id_kategori_produk').val(res.id_kategori_produk)
                    $('#edit_id_kategori_produk').trigger('change');
                    $('#edit_merek_produk').val(res.merek_produk)
                    $('#edit_nama_produk').val(res.nama_produk)
                    $('#edit_berat').val(res.berat)
                    $('#edit_harga').val(res.harga)
                    $('#edit_harga').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });

                    $('#edit_deskripsi').summernote('code', res.deskripsi)

                    $('#edit_foto2').attr('src', ``)
                    if (res.photo) {
                        $('#edit_foto2').attr('src', `/storage/${res.photo.path_foto}`)
                    }

                    $('#modalEdit').modal('show');
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })

        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            formData.append('id_kategori_produk', formData.get('edit_id_kategori_produk'));
            formData.append('merek_produk', formData.get('edit_merek_produk'));
            formData.append('nama_produk', formData.get('edit_nama_produk'));
            formData.append('berat', formData.get('edit_berat'));
            formData.append('harga', formData.get('edit_harga'));
            formData.append('deskripsi', formData.get('edit_deskripsi'));
            formData.append('path_foto', formData.get('edit_foto'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_id_kategori_produk');
            formData.delete('edit_merek_produk');
            formData.delete('edit_nama_produk');
            formData.delete('edit_berat');
            formData.delete('edit_harga');
            formData.delete('edit_deskripsi');
            formData.delete('edit_foto');


            $.ajax({
                type: 'POST',
                data : formData,
                contentType: false,
                processData: false,
                url: $(this).attr('action'),
                beforeSend:function(){
                    $('#btn-update').addClass("btn-progress");
                    $(document).find('span.error-text').text('');
                },
                complete: function(){
                    $('#btn-update').removeClass("btn-progress");
                },
                success: function(res){
                    if(res.success == true){

                        location.reload();
                        $('#modalEdit').modal('hide');

                        $('#formDataEdit').trigger('reset');
                        $('#example').DataTable().ajax.reload();

                        swal(res.message.title, res.message.content, res.message.type);
                    }
                },
                error(err){
                    $.each(err.responseJSON,function(prefix,val) {
                        $('.'+prefix+'_error_edit').text(val[0]);
                    })
                }
            })
        })

        $(document).on('click','button#btn-delete',function() {
            let id = $(this).data('id');

            swal({
                title: 'Anda Yakin?',
                text: 'Anda akan menghapus data barang product',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `products/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data barang product berhasil dihapus', {
                                    icon: 'success',
                                });

                                location.reload();
                            }
                        },
                        error:function(err){
                            swal({
                                icon: 'error',
                                title: 'Terjadi kesalahan',
                                text: err.responseJSON.message+'.',
                            })
                        }
                    });
                }
            });
        });
    </script>


@endpush
