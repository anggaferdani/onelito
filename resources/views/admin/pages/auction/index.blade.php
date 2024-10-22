@extends('admin.layouts.app')

@section('title', 'Auction')

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
        href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Auction</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Auction</div>
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
                            ><i class="fa fa-plus"></i> Tambah Auction</button>

                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Kategori Event</th>
                                                <th>Rules</th>
                                                <th>Tgl. Mulai</th>
                                                <th>Tgl. Akhir</th>
                                                <th>Banner</th>
                                                <th>Auction ditutup</th>
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
        @include('admin.pages.auction._create')
        @include('admin.pages.auction._show')
        @include('admin.pages.auction._edit')
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    {{-- <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script> --}}
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <!-- <script src="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.js') }}"></script> -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <script src="https://demo.getstisla.com/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
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

            // $('.summernote').summernote({
            //     height: 200,
            //     toolbar: [
            //         ['style',['style']],
            //         ['style', ['bold', 'italic', 'underline', 'clear']],
            //         ['font', ['strikethrough', 'superscript', 'subscript']],
            //         ['fontsize', ['fontsize']],
            //         ['color', ['color']],
            //         ['para', ['ul', 'ol', 'paragraph']],
            //         ['insert',['picture', 'link', 'emoji']]
            //     ]
            // });

            $('#total_hadiah').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#edit_total_hadiah').priceFormat({
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
                url : '{{ url("admin/auctions") }}',
                data : function(d) {
                    // d.jenis_task = $('#filter_jenis_task').val()
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'kategori_event'},
                    { data : 'rules_event'},
                    { data : 'tgl_mulai'},
                    { data : 'tgl_akhir'},
                    { data : 'banner'},
                    { data : 'text_status_tutup'},
                    { data : 'action' , orderable : false,searchable :false}
                ]
            });
        });

        $(document).on('click','button#btn-show',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `auctions/${id}`,
                dataType: "json",
                success: function(res) {
                    $('#modalShow').modal('show');
                    $('#show_kategori_event').val(res.kategori_event)
                    $('#show_rules_event').html(res.rules_event)
                    $('#show_deskripsi_event').val(res.deskripsi_event)
                    $('#show_tgl_mulai').val(res.tgl_mulai)
                    $('#show_tgl_akhir').val(res.tgl_akhir)
                    $('#show_total_hadiah').val(res.total_hadiah)

                    $('#show_banner').attr('src', ``)

                    if (res.banner) {
                        $('#show_banner').attr('src', `/storage/${res.banner}`)
                    }

                    var dt_auction_products = ``;
                    if (res.auction_products) {

                        $.each(res.auction_products,function(prefix,val) {
                            var photo = '/img/news/img01.jpg';

                            if (val.photo) {
                                photo = `/storage/${val.photo.path_foto}`
                            }

                            dt_auction_products += `<div class="col-6 col-sm-4">
                                                <label class="mb-4">
                                                    <img width="225" height="161" src="${photo}"
                                                        alt="}"
                                                        class="imagecheck-image">
                                                </label>
                                                <br>
                                                <label>No Ikan: ${val.no_ikan}</label>
                                                <br>
                                                <label>Variety: ${val.variety}</label>
                                                <br>
                                                <label>Bloodline: ${val.bloodline}</label>
                                            </div>`;
                        })
                    }

                    $('#show_auction_products').html(dt_auction_products);
                },
                error:function(error) {
                }

            })
        })

        $(document).on('click','button#btn-edit',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `auctions/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `auctions/${id}`;
                    $('#modalEdit').modal('show');
                    $('#edit_kategori_event').val(res.kategori_event)
                    $('#edit_kategori_event').html(`
                        <option value="Regular" ${((res.kategori_event === 'Regular') ? 'selected' : '')}>Regular</option>
                        <option value="Event" ${((res.kategori_event === 'Event') ? 'selected' : '')}>Event</option>
                    `)
                    $('#edit_rules_event').summernote('code', res.rules_event)
                    $('#edit_deskripsi_event').val(res.deskripsi_event)
                    $('#edit_tgl_mulai').val(res.tgl_mulai)
                    $('#edit_tgl_akhir').val(res.tgl_akhir)
                    $('#edit_banner2').attr('src', ``)

                    if (res.kategori_event === 'Regular') {
                        $('.banner').addClass('d-none');
                        $('.total_hadiah').addClass('d-none');
                    }

                    if (res.banner) {
                        $('#edit_banner2').attr('src', `/storage/${res.banner}`)
                    }

                    $('#edit_total_hadiah').val(res.total_hadiah)
                    $('#edit_total_hadiah').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });

                    var editProducts = res.auction_products.map(o => o['id_ikan']);
                    $('#edit_auction_products').val(editProducts);
                    $('#edit_auction_products').trigger('change');
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })

        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            formData.append('kategori_event', formData.get('edit_kategori_event'));
            formData.append('deskripsi_event', formData.get('edit_deskripsi_event'));
            formData.append('rules_event', formData.get('edit_rules_event'));
            formData.append('tgl_mulai', formData.get('edit_tgl_mulai'));
            formData.append('tgl_akhir', formData.get('edit_tgl_akhir'));
            formData.append('banner', formData.get('edit_banner'));
            formData.append('total_hadiah', formData.get('edit_total_hadiah'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_kategori_event');
            formData.delete('edit_deskripsi_event');
            formData.delete('edit_rules_event');
            formData.delete('edit_tgl_mulai');
            formData.delete('edit_tgl_akhir');
            formData.delete('edit_total_hadiah');
            formData.delete('edit_banner');

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

        $(document).on('change', 'select#kategori_event', function () {
            var val = $(this).val()

            if (val === 'Regular') {
                $('.banner').addClass('d-none');
                $('.total_hadiah').addClass('d-none');
            }

            if (val === 'Event') {
                $('.banner').removeClass('d-none');
                // $('.total_hadiah').removeClass('d-none');
            }
        })

        $(document).on('change', 'select#edit_kategori_event', function () {
            var val = $(this).val()

            if (val === 'Regular') {
                $('.banner').addClass('d-none');
                $('.total_hadiah').addClass('d-none');
            }

            if (val === 'Event') {
                $('.banner').removeClass('d-none');
                // $('.total_hadiah').removeClass('d-none');
            }
        })

        $(document).on('click','button#btn-delete',function() {
            let id = $(this).data('id');

            swal({
                title: 'Anda Yakin?',
                text: 'Anda akan menghapus data Auction',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `auctions/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data Auction berhasil dihapus', {
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

        $(document).on('click','button#btn-close',function() {
            let id = $(this).data('id');

            swal({
                title: 'Anda akan menutup data Auction',
                text: 'Auction yang ditutup tidak akan ditampilkan di halaman depan',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'PATCH',
                        dataType: 'JSON',
                        url: `auctions/${id}?action=close-auction`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data Auction berhasil ubah', {
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
