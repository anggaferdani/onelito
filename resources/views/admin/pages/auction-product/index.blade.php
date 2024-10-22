@extends('admin.layouts.app')

@section('title', 'Barang Lelang')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Barang Lelang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Barang Lelang</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCreate"><i
                                        class="fa fa-plus"></i> Tambah Barang Lelang</button>

                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
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
                                                <th>Extra Time</th>
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
        @include('admin.pages.auction-product._create')
        @include('admin.pages.auction-product._show')
        @include('admin.pages.auction-product._edit')
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js">
    </script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

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
            $('#ob').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#kb').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#edit_ob').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });

            $('#edit_kb').priceFormat({
                prefix: '',
                centsLimit: 0,
                thousandsSeparator: '.'
            });
            $('#table-1').DataTable({
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
                buttons: [
                    'pageLength', 'csv', 'excel', 'pdf', 'print',
                ],
                "responsive": true,
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                search: {
                    return: true
                },
                ajax: {
                    url: '{{ url('admin/auction-products') }}',
                    data: function(d) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_ikan'
                    },
                    {
                        data: 'variety'
                    },
                    {
                        data: 'breeder'
                    },
                    {
                        data: 'bloodline'
                    },
                    {
                        data: 'sex'
                    },
                    {
                        data: 'dob'
                    },
                    {
                        data: 'size'
                    },
                    {
                        data: 'extra_time'
                    },
                    {
                        data: 'photo',
                        name: 'photo.path_foto'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(document).on('click', 'button#btn-show', function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: `auction-products/${id}`,
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
                    $('#show_ob').val(res.currency.symbol + ' ' + res.ob)
                    $('#show_kb').val(res.currency.symbol + ' ' + res.kb)
                    $('#show_note').html(res.note)
                    $('#show_link_video').val(res.link_video)
                    $('#show_extra_time').val(res.extra_time)
                    $('#show_foto').attr('src', ``)
                    if (res.photo.path_foto) {
                        $('#show_foto').attr('src', `/storage/${res.photo.path_foto}`)
                    }
                },
                error: function(error) {
                    console.log(error)
                }

            })
        })

        $(document).on('click', 'button#btn-edit', function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: `auction-products/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `auction-products/${id}`;
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
                    $('#edit_currency_id').val(res.currency.id)
                    $('#edit_currency_id').trigger('change')
                    $('#edit_ob').val(res.ob)
                    $('#edit_ob').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    $('#edit_kb').val(res.kb)
                    $('#edit_kb').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    $('#edit_note').summernote('code', res.note)
                    $('#edit_link_video').val(res.link_video)
                    $('#edit_extra_time').val(res.extra_time)

                    $('#edit_foto2').attr('src', ``)

                    if (res.photo.path_foto) {
                        $('#edit_foto2').attr('src', `/storage/${res.photo.path_foto}`)
                    }
                },
                error: function(error) {
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
            formData.append('currency_id', formData.get('edit_currency_id'));
            formData.append('ob', formData.get('edit_ob'));
            formData.append('kb', formData.get('edit_kb'));
            formData.append('note', formData.get('edit_note'));
            formData.append('link_video', formData.get('edit_link_video'));
            formData.append('path_foto', formData.get('edit_foto'));
            formData.append('extra_time', formData.get('edit_extra_time'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_no_ikan');
            formData.delete('edit_variety');
            formData.delete('edit_breeder');
            formData.delete('edit_bloodline');
            formData.delete('edit_sex');
            formData.delete('edit_dob');
            formData.delete('edit_size');
            formData.delete('edit_currency_id');
            formData.delete('edit_ob');
            formData.delete('edit_kb');
            formData.delete('edit_note');
            formData.delete('edit_link_video');
            formData.delete('edit_foto');
            formData.delete('edit_extra_time');


            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                url: $(this).attr('action'),
                beforeSend: function() {
                    $('#btn-update').addClass("btn-progress");
                    $(document).find('span.error-text').text('');
                },
                complete: function() {
                    $('#btn-update').removeClass("btn-progress");
                },
                success: function(res) {
                    if (res.success == true) {

                        location.reload();
                        $('#modalEdit').modal('hide');

                        $('#formDataEdit').trigger('reset');
                        $('#example').DataTable().ajax.reload();

                        swal(res.message.title, res.message.content, res.message.type);
                    }
                },
                error(err) {
                    $.each(err.responseJSON, function(prefix, val) {
                        $('.' + prefix + '_error_edit').text(val[0]);
                    })
                }
            })
        })

        $(document).on('click', 'button#btn-delete', function() {
            let id = $(this).data('id');

            swal({
                    title: 'Anda Yakin?',
                    text: 'Anda akan menghapus data barang lelang',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: 'DELETE',
                            dataType: 'JSON',
                            url: `auction-products/${id}`,
                            data: {
                                "_token": $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                if (response.success) {
                                    swal('Data barang lelang berhasil dihapus', {
                                        icon: 'success',
                                    });

                                    location.reload();
                                }
                            },
                            error: function(err) {
                                swal({
                                    icon: 'error',
                                    title: 'Terjadi kesalahan',
                                    text: err.responseJSON.message + '.',
                                })
                            }
                        });
                    }
                });
        });
    </script>
@endpush
