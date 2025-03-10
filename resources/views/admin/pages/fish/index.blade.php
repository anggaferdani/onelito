@extends('admin.layouts.app')

@section('title', 'Fish')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
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
                                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCreate">
                                    <i class="fa fa-plus"></i> Tambah Fish
                                </button>

                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
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
                                            <!-- DataTables will populate this -->
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
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- bootsrap datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/js/price-separator.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

    <script>
        $(document).ready(function() {
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

            // DataTable initialization
            var table = $('#table-1').DataTable({
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
                responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                search: {
                    return: true
                },
                ajax: {
                    url: '{{ url("admin/fishes") }}',
                    data: function(d) {
                        // d.jenis_task = $('#filter_jenis_task').val()
                    }
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
                        data: 'weight_input', // Display the input field
                        name: 'weight',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'stock_input', // Display the input field
                        name: 'stock',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'harga_ikan'
                    },
                    {
                        data: 'foto_ikan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                 initComplete: function() {
                    // After DataTable is initialized, bind the event listeners
                    bindInlineEditEvents(table);
                }
            });

            function bindInlineEditEvents(table) {
                // Stock Update
                $(document).off('change', '.edit-stock').on('change', '.edit-stock', function() {
                    let fishId = $(this).data('id');
                    let newStock = $(this).val();
                    let $input = $(this);

                    $.ajax({
                        url: '/admin/fishes/' + fishId + '/update-stock',
                        type: 'POST',
                        data: {
                            stock: newStock,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Stock updated successfully'
                                });
                                // Update the cell data directly
                                let cell = table.cell($input.closest('td'));
                                cell.data(response.data); // Update the data source
                                $input.val(response.data); // Update input value
                                cell.invalidate(); // Redraw the cell
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update stock'
                            });
                        }
                    });
                });

                // Weight Update
                $(document).off('change', '.edit-weight').on('change', '.edit-weight', function() {
                    let fishId = $(this).data('id');
                    let newWeight = $(this).val();
                    let $input = $(this);

                    $.ajax({
                        url: '/admin/fishes/' + fishId + '/update-weight',
                        type: 'POST',
                        data: {
                            weight: newWeight,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Weight updated successfully'
                                });

                                 let cell = table.cell($input.closest('td'));
                                cell.data(response.data); // Update the data source
                                $input.val(response.data); // Update input value
                                cell.invalidate(); // Redraw the cell

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update weight'
                            });
                        }
                    });
                });
            }

            $(document).on('click', 'button#btn-show', function() {
                let id = $(this).data('id');
                let dataUrl = $(this).data('url');
                $.ajax({
                    type: 'GET',
                    url: `fishes/${id}`,
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
                    url: `fishes/${id}`,
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
                    error: function(error) {
                        console.log(error)
                    }

                })
            })

            $('#formEdit').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

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
                            $('#formDataEdit').trigger('reset');
                            table.ajax.reload(null, false); // Reload the DataTable

                            Swal.fire({
                                icon: 'success',
                                title: res.message.title,
                                text: res.message.content,
                                timer: 1500
                            });
                        }
                    },
                    error: function(err) {
                        $.each(err.responseJSON, function(prefix, val) {
                            $('.' + prefix + '_error_edit').text(val[0]);
                        });
                    }
                })
            })

            $(document).on('click', 'button#btn-delete', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: 'Anda akan menghapus data ikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            dataType: 'JSON',
                            url: `fishes/${id}`,
                            data: {
                                "_token": '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Data ikan berhasil dihapus.',
                                        'success'
                                    )
                                    table.ajax.reload(null, false); // Reload the DataTable
                                }
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi kesalahan',
                                    text: err.responseJSON.message + '.',
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
        });
    </script>


@endpush