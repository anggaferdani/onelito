@extends('admin.layouts.app')

@section('title', 'Fish')

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
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">

@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Fish</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Fish</div>
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
                            ><i class="fa fa-plus"></i> Tambah Fish</button>

                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>No. Ikan</th>
                                                <th>Variety</th>
                                                <th>Breeder</th>
                                                <th>Bloodline</th>
                                                <th>Jenis Kelamin</th>
                                                <th>DOB</th>
                                                <th>Size</th>
                                                <th>Weight (gr)</th>
                                                <th>Stock</th>
                                                <th>Harga</th>
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
        @include('admin.pages.fish._create')
        @include('admin.pages.fish._show')
        @include('admin.pages.fish._edit')
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

    <!-- bootsrap datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
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

            // $("#dob").datepicker( {
            //     format: "mm-yyyy",
            //     startView: "months",
            //     minViewMode: "months"
            // });

            // $("#edit_dob").datepicker( {
            //     format: "mm-yyyy",
            //     startView: "months",
            //     minViewMode: "months"
            // });

            $('#harga_ikan').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#edit_harga_ikan').priceFormat({
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
                url : '{{ url("admin/fishes") }}',
                data : function(d) {
                    // d.jenis_task = $('#filter_jenis_task').val()
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'no_ikan' },
                    { data : 'variety'},
                    { data : 'breeder'},
                    { data : 'bloodline'},
                    { data : 'sex'},
                    { data : 'dob'},
                    { data : 'size'},
                    { data : 'weight'},
                    { data : 'stock'},
                    { data : 'harga_ikan'},
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
                url : `fishes/${id}`,
                dataType: "json",
                success: function(res) {
                    $('#modalShow').modal('show');
                    $('#show_no_ikan').val(res.no_ikan)
                    $('#show_variety').val(res.variety)
                    $('#show_breeder').val(res.breeder)
                    $('#show_bloodline').val(res.bloodline)
                    $('#show_sex').val(res.sex)
                    $('#show_dob').val(res.dob)
                    $('#show_size').val(res.size)
                    $('#show_weight').val(res.weight)
                    $('#show_height').val(res.height)
                    $('#show_length').val(res.length)
                    $('#show_width').val(res.width)
                    $('#show_point').val(res.point)
                    $('#show_stock').val(res.stock)
                    $('#show_harga_ikan').val(res.harga_ikan)
                    $('#show_note').html(res.note)
                    $('#show_link_video').val(res.link_video)

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
                url : `fishes/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `fishes/${id}`;
                    $('#modalEdit').modal('show');
                    $('#edit_no_ikan').val(res.no_ikan)
                    $('#edit_variety').val(res.variety)
                    $('#edit_breeder').val(res.breeder)
                    $('#edit_bloodline').val(res.bloodline)
                    $('#edit_sex').html(`
                        <option value="Male" ${((res.sex === 'Male') ? 'selected' : '')}>Male</option>
                        <option value="Female" ${((res.sex === 'Female') ? 'selected' : '')}>Female</option>
                        <option value="Unknown" ${((res.sex === 'Unknown') ? 'selected' : '')}>Unknown</option>
                    `)
                    $('#edit_dob').val(res.dob)
                    $('#edit_size').val(res.size)
                    $('#edit_weight').val(res.weight)
                    $('#edit_height').val(res.height)
                    $('#edit_length').val(res.length)
                    $('#edit_width').val(res.width)
                    $('#edit_stock').val(res.stock)
                    $('#edit_harga_ikan').val(res.harga_ikan)
                    $('#edit_harga_ikan').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    // Calculate and set the percent value
                    let hargaIkan = parseFloat(res.harga_ikan.replace(/\./g, '')); // Remove thousand separators
                    let point = parseFloat(res.point.replace(/\./g, '')); // Remove thousand separators
                    let percent = (point / hargaIkan) * 100;
                    $('#edit_percent').val(percent); // Display with 2 
                    $('#edit_point').val(point); // Display with 2 
                    $('#edit_point').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    $('#edit_note').summernote('code', res.note)
                    $('#edit_link_video').val(res.link_video)

                    $('#edit_foto2').attr('src', ``)

                    if (res.foto_ikan) {
                        $('#edit_foto2').attr('src', `/storage/${res.foto_ikan}`)
                    }

                    $('#edit_harga_ikan').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })

        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            formData.append('no_ikan', formData.get('edit_no_ikan'));
            formData.append('variety', formData.get('edit_variety'));
            formData.append('breeder', formData.get('edit_breeder'));
            formData.append('bloodline', formData.get('edit_bloodline'));
            formData.append('sex', formData.get('edit_sex'));
            formData.append('dob', formData.get('edit_dob'));
            formData.append('size', formData.get('edit_size'));
            formData.append('weight', formData.get('edit_weight'));
            formData.append('height', formData.get('edit_height'));
            formData.append('length', formData.get('edit_length'));
            formData.append('width', formData.get('edit_width'));
            formData.append('point', formData.get('edit_point'));
            formData.append('stock', formData.get('edit_stock'));
            formData.append('harga_ikan', formData.get('edit_harga_ikan'));
            formData.append('note', formData.get('edit_note'));
            formData.append('link_video', formData.get('edit_link_video'));
            formData.append('path_foto', formData.get('edit_foto'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_no_ikan');
            formData.delete('edit_variety');
            formData.delete('edit_breeder');
            formData.delete('edit_bloodline');
            formData.delete('edit_sex');
            formData.delete('edit_dob');
            formData.delete('edit_size');
            formData.delete('edit_weight');
            formData.delete('edit_height');
            formData.delete('edit_length');
            formData.delete('edit_width');
            formData.delete('edit_stock');
            formData.delete('edit_point');
            formData.delete('edit_harga_ikan');
            formData.delete('edit_note');
            formData.delete('edit_link_video');
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
                text: 'Anda akan menghapus data ikan',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `fishes/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data ikan berhasil dihapus', {
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

        $(document).on('input', '#harga_ikan, #percent', function() {
            let harga_ikan = $('#harga_ikan').val().replace(/\./g, '');
            let percent = $('#percent').val();

            // Validate percent is not greater than 100
            if (percent > 100) {
                $('#percent').val(100); // Set percent to 100 if it exceeds
                percent = 100; // Update the local percent variable
            }

            if (harga_ikan && percent) {
                let point = (parseInt(harga_ikan) * parseInt(percent)) / 100;
                // Format the point value to "1.000.000" format
                let formattedPoint = point.toLocaleString('id-ID');
                $('#point').val(formattedPoint);
            } else {
                $('#point').val('');
            }
        });

        $(document).on('input', '#edit_harga_ikan, #edit_percent', function() {
            let harga_ikan = $('#edit_harga_ikan').val().replace(/\./g, '');
            let percent = $('#edit_percent').val();

            // Validate percent is not greater than 100
            if (percent > 100) {
                $('#edit_percent').val(100); // Set percent to 100 if it exceeds
                percent = 100; // Update the local percent variable
            }

            if (harga_ikan && percent) {
                let point = (parseInt(harga_ikan) * parseInt(percent)) / 100;
                // Format the point value to "1.000.000" format
                let formattedPoint = point.toLocaleString('id-ID');
                $('#edit_point').val(formattedPoint);
            } else {
                $('#edit_point').val('');
            }
        });
    </script>


@endpush