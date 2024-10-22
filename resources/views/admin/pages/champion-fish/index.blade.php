@extends('admin.layouts.app')

@section('title', 'Champion Koi')

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
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Champion Koi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Champion Koi</div>
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
                            ><i class="fa fa-plus"></i> Tambah Champion Koi</button>

                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <!-- <th>Nama Champion</th> -->
                                                <!-- <th>Tahun</th> -->
                                                <!-- <th>Size</th> -->
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
        @include('admin.pages.champion-fish._create')
        @include('admin.pages.champion-fish._show')
        @include('admin.pages.champion-fish._edit')
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

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {
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
                url : '{{ url("admin/champion-fishes") }}',
                data : function(d) {
                    // filter here
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    // { data : 'nama_champion' },
                    // { data : 'tahun'},
                    // { data : 'size'},
                    { data : 'foto_ikan', orderable : false,searchable :false},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });
        });

        $(document).on('click','button#btn-show',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `champion-fishes/${id}`,
                dataType: "json",
                success: function(res) {
                    $('#modalShow').modal('show');
                    $('#show_nama_champion').val(res.nama_champion)
                    $('#show_tahun').val(res.tahun)
                    $('#show_size').val(res.size)

                    $('#show_foto').attr('src', ``)

                    if (res.foto_ikan) {
                        $('#show_foto').attr('src', `/storage/${res.foto_ikan}`)
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
                url : `champion-fishes/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `champion-fishes/${id}`;
                    $('#modalEdit').modal('show');
                    $('#edit_nama_champion').val(res.nama_champion)
                    $('#edit_tahun').val(res.tahun)
                    $('#edit_size').val(res.size)

                    $('#edit_foto2').attr('src', ``)

                    if (res.foto_ikan) {
                        $('#edit_foto2').attr('src', `/storage/${res.foto_ikan}`)
                    }
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })

        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            formData.append('nama_champion', formData.get('edit_nama_champion'));
            formData.append('tahun', formData.get('edit_tahun'));
            formData.append('size', formData.get('edit_size'));
            formData.append('path_foto', formData.get('edit_foto'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_nama_champion');
            formData.delete('edit_tahun');
            formData.delete('edit_size');
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
                text: 'Anda akan menghapus data Champion Koi',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `champion-fishes/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data Champion Koi berhasil dihapus', {
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
