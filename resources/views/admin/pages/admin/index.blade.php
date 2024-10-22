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
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Admin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Admin</div>
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
                            ><i class="fa fa-plus"></i> Tambah Admin</button>

                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>No. hp</th>
                                                <th>Level</th>
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
        @include('admin.pages.admin._create')
        @include('admin.pages.admin._show')
        @include('admin.pages.admin._edit')
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
                url : '{{ url("admin/admins") }}',
                data : function(d) {
                    // d.jenis_task = $('#filter_jenis_task').val()
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'nama' },
                    { data : 'username', searchable: false },
                    { data : 'no_hp', searchable: false},
                    { data : 'level' , orderable : false,searchable :false},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });

            $(document).on('click','button#btn-show',function() {
                let id = $(this).data('id');
                let dataUrl = $(this).data('url');
                $.ajax({
                    type: 'GET',
                    url : `admins/${id}`,
                    dataType: "json",
                    success: function(res) {
                        $('#modalShow').modal('show');
                        $('#show_nama').val(res.nama)
                        $('#show_username').val(res.nama)
                        $('#show_email').val(res.email)
                        $('#show_no_hp').val(res.no_hp)
                        $('#show_alamat').val(res.alamat)
                        $('#show_kota').val(res.kota)
                        $('#show_level').val(res.level)
                    },
                    error:function(error) {
                        console.log(error)
                    }

                })
            })

            $(document).on('click','button#btn-edit',async function() {
                let id = $(this).data('id');
                let dataUrl = $(this).data('url');
                $.ajax({
                    type: 'GET',
                    url : `admins/${id}`,
                    dataType: "json",
                    success:async function(res) {

                        document.getElementById('formEdit').action = `admins/${id}`;
                        $('#modalEdit').modal('show');
                        $('#edit_nama').val(res.nama)
                        $('#edit_username').val(res.username)
                        $('#edit_email').val(res.email)
                        $('#edit_no_hp').val(res.no_hp)
                        $('#edit_alamat').val(res.alamat)
                        $('#edit_kota').val(res.kota)
                        $('#edit_level').val(res.level)
                        $('#edit_level').trigger('change');
                    },
                    error:function(error) {
                        console.log(error)
                    }

                })
            })

            $('#formEdit').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                var dataForm = {
                    nama: formData.get('edit_nama'),
                    username: formData.get('edit_username'),
                    email: formData.get('edit_email'),
                    no_hp: formData.get('edit_no_hp'),
                    alamat: formData.get('edit_alamat'),
                    kota: formData.get('edit_kota'),
                    level: formData.get('edit_level'),
                }

                $.ajax({
                    type: 'PATCH',
                    data : dataForm,
                    dataType: 'json',
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
                    text: 'Anda akan menghapus data admin',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type:'DELETE',
                            dataType: 'JSON',
                            url: `admins/${id}`,
                            data:{
                                "_token": $('meta[name="csrf-token"]').attr('content'),
                            },
                            success:function(response){
                                if(response.success){
                                    swal('Data admin berhasil dihapus', {
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
        });
    </script>


@endpush
