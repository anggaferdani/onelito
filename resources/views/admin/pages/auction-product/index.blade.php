@extends('admin.layouts.app')

@section('title', 'Barang Lelang')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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
        // Global variable for current edit request
        let currentEditRequest = null;

        $(document).ready(function() {
            // Price format initialization with null check
            function initializePriceFormat(selector) {
                $(selector).each(function() {
                    if ($(this).val() === null || $(this).val() === undefined) {
                        $(this).val('0');
                    }
                    $(this).priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                });
            }

            // Initialize price format for all price fields
            initializePriceFormat('#ob');
            initializePriceFormat('#kb');
            initializePriceFormat('#edit_ob');
            initializePriceFormat('#edit_kb');

            // DataTable initialization
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

        // Reset modal when closed
        $('#modalEdit').on('hidden.bs.modal', function () {
            // Reset form
            $('#formEdit')[0].reset();
            
            // Clear image
            $('#edit_foto2').attr('src', '');
            
            // Reset TinyMCE if exists
            if (typeof tinymce !== 'undefined' && tinymce.get('edit_note')) {
                tinymce.get('edit_note').setContent('');
            }
            
            // Reset select2 if used
            $('#edit_currency_id').val('').trigger('change');
            $('#edit_sex').html('');
            
            // Reset validation errors
            $(document).find('span.error-text').text('');
            
            // Re-enable all edit buttons
            $('button#btn-edit').prop('disabled', false);
        });

        // Reset show modal when closed
        $('#modalShow').on('hidden.bs.modal', function () {
            // Clear show modal data
            $('#modalShow').find('input, textarea').val('');
            $('#show_foto').attr('src', '');
        });

        // Show modal handler
        $(document).on('click', 'button#btn-show', function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            
            // Clear previous data
            $('#modalShow').find('input, textarea').val('');
            $('#show_foto').attr('src', '');
            
            $.ajax({
                type: 'GET',
                url: `auction-products/${id}`,
                dataType: "json",
                cache: false,
                timeout: 10000,
                success: function(res) {
                    $('#modalShow').modal('show');
                    $('#show_no_ikan').val(res.no_ikan || '');
                    $('#show_variety').val(res.variety || '');
                    $('#show_breeder').val(res.breeder || '');
                    $('#show_bloodline').val(res.bloodline || '');
                    $('#show_sex').val(res.sex || '');
                    $('#show_dob').val(res.dob || '');
                    $('#show_size').val(res.size || '');
                    $('#show_ob').val((res.currency ? res.currency.symbol : '') + ' ' + (res.ob || '0'));
                    $('#show_kb').val((res.currency ? res.currency.symbol : '') + ' ' + (res.kb || '0'));
                    $('#show_note').html(res.note || '');
                    $('#show_link_video').val(res.link_video || '');
                    $('#show_extra_time').val(res.extra_time || '');
                    
                    if (res.photo && res.photo.path_foto) {
                        $('#show_foto').attr('src', `/storage/${res.photo.path_foto}`);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    swal('Error', 'Gagal memuat data. Silakan coba lagi.', 'error');
                }
            });
        });

        // Edit modal handler - FIXED VERSION
        $(document).on('click', 'button#btn-edit', function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            let $button = $(this);
            
            // Abort previous request if exists
            if (currentEditRequest && currentEditRequest.readyState !== 4) {
                currentEditRequest.abort();
            }
            
            // Disable all edit buttons to prevent multiple clicks
            $('button#btn-edit').prop('disabled', true);
            
            // Clear form data first
            $('#modalEdit').find('input, select, textarea').val('');
            $('#edit_foto2').attr('src', '');
            $('#edit_sex').html('');
            
            // Reset TinyMCE if exists
            if (typeof tinymce !== 'undefined' && tinymce.get('edit_note')) {
                tinymce.get('edit_note').setContent('');
            }
            
            // Add timestamp to prevent caching
            let timestamp = new Date().getTime();
            
            currentEditRequest = $.ajax({
                type: 'GET',
                url: `auction-products/${id}?t=${timestamp}`,
                dataType: "json",
                cache: false,
                timeout: 10000,
                beforeSend: function() {
                    // Show loading indicator
                    $button.html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(res) {
                    // Set form action
                    document.getElementById('formEdit').action = `auction-products/${id}`;
                    
                    // Show modal
                    $('#modalEdit').modal('show');
                    
                    // Populate form fields (handle null/undefined values)
                    $('#edit_no_ikan').val(res.no_ikan || '');
                    $('#edit_variety').val(res.variety || '');
                    $('#edit_breeder').val(res.breeder || '');
                    $('#edit_bloodline').val(res.bloodline || '');
                    
                    // Set sex dropdown
                    $('#edit_sex').html(`
                        <option value="Male" ${((res.sex === 'Male') ? 'selected' : '')}>Male</option>
                        <option value="Female" ${((res.sex === 'Female') ? 'selected' : '')}>Female</option>
                        <option value="Unknown" ${((res.sex === 'Unknown') ? 'selected' : '')}>Unknown</option>
                    `);
                    
                    $('#edit_dob').val(res.dob || '');
                    $('#edit_size').val(res.size || '');
                    $('#edit_currency_id').val(res.currency ? res.currency.id : '');
                    $('#edit_currency_id').trigger('change');
                    
                    // Set price fields with formatting (handle null/undefined values)
                    let obValue = res.ob || '0';
                    let kbValue = res.kb || '0';
                    
                    $('#edit_ob').val(obValue);
                    $('#edit_ob').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    
                    $('#edit_kb').val(kbValue);
                    $('#edit_kb').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    
                    // Set TinyMCE content (handle null/undefined)
                    if (typeof tinymce !== 'undefined' && tinymce.get('edit_note')) {
                        tinymce.get('edit_note').setContent(res.note || '');
                    }
                    
                    $('#edit_link_video').val(res.link_video || '');
                    $('#edit_extra_time').val(res.extra_time || '');
                    
                    // Set image (handle null/undefined)
                    if (res.photo && res.photo.path_foto) {
                        $('#edit_foto2').attr('src', `/storage/${res.photo.path_foto}`);
                    }
                },
                error: function(xhr, status, error) {
                    if (status !== 'abort') {
                        console.log('Error:', error);
                        swal('Error', 'Gagal memuat data. Silakan coba lagi.', 'error');
                    }
                },
                complete: function() {
                    // Reset button text and re-enable
                    $button.html('<i class="fa fa-pen"></i>');
                    $('button#btn-edit').prop('disabled', false);
                }
            });
        });

        // Edit form submit handler
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            
            // Trigger TinyMCE save
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            
            let formData = new FormData(this);
            formData.append('_method', 'PATCH');

            // Append form data with correct field names
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

            // Remove old field names
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
                        $('#modalEdit').modal('hide');
                        $('#formEdit')[0].reset();
                        $('#table-1').DataTable().ajax.reload(null, false);
                        swal(res.message.title, res.message.content, res.message.type);
                    }
                },
                error: function(err) {
                    $.each(err.responseJSON, function(prefix, val) {
                        $('.' + prefix + '_error_edit').text(val[0]);
                    });
                }
            });
        });

        // Delete handler
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
                                    $('#table-1').DataTable().ajax.reload(null, false);
                                    swal('Data barang lelang berhasil dihapus', {
                                        icon: 'success',
                                    });
                                }
                            },
                            error: function(err) {
                                swal({
                                    icon: 'error',
                                    title: 'Terjadi kesalahan',
                                    text: err.responseJSON.message + '.',
                                });
                            }
                        });
                    }
                });
        });
    </script>
@endpush