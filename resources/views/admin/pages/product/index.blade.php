@extends('admin.layouts.app')
@section('title', 'Barang Store')
@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">

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
                                                <th>Weight (gr)</th>
                                                <th>Stock</th>
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
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/price-separator.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>



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

              // Inisialisasi DataTable (Pastikan ini ada jika belum ada)
            var table = $('#table-1').DataTable({
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
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'category', name: 'category.kategori_produk' },
                    { data : 'merek_produk'},
                    { data : 'nama_produk'},
                    { data : 'berat'},
                    { data : 'weight'},
                    { data : 'stock', name: 'stock'},
                    { data : 'harga'},
                    { data : 'deskripsi'},
                    { data : 'photo', name: 'photo.path_foto', orderable : false,searchable :false},
                    { data : 'action' , orderable : false,searchable :false},
                ],
                "columnDefs": [
                    {
                        "targets": 5, // Index of the 'Weight' column (Weight column index)
                        "render": function (data, type, row) {
                            if (type === 'display') {
                                return '<input type="number" class="form-control edit-weight" style="min-width: 100px !important;" data-id="' + row.id_produk + '" value="' + data + '">';
                            }
                            return data;
                        }
                    },
                    {
                        "targets": 6, // Index of the 'Stock' column
                        "render": function (data, type, row) {
                            if (type === 'display') {
                                return '<input type="number" class="form-control edit-stock" style="min-width: 100px !important;" data-id="' + row.id_produk + '" value="' + data + '">';
                            }
                            return data;
                        }
                    }
                ]
            });

            $(document).on('change', '.edit-stock', function() {
                let productId = $(this).data('id');
                let newStock = $(this).val();
                let $input = $(this);
                //let table = $('#table-1').DataTable(); // Sudah diinisialisasi di atas
                //let currentPage = table.page(); // Tidak perlu disimpan lagi

                $.ajax({
                    url: '/admin/products/' + productId + '/update-stock',
                    type: 'POST',
                    data: {
                        stock: newStock,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swal('Success', 'Stock updated successfully', 'success');
                            // Update the cell data directly
                            let cell = table.cell($input.closest('td'));
                            cell.data(response.data); // Update the data source
                            $input.val(response.data);    // Update input value
                            cell.invalidate();
                        } else {
                            swal('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        swal('Error', 'Failed to update stock', 'error');
                    }
                });
            });


            $(document).on('change', '.edit-weight', function() {
                let productId = $(this).data('id');
                let newWeight = $(this).val();
                let $input = $(this);
                //let table = $('#table-1').DataTable(); // Sudah diinisialisasi di atas
                //let currentPage = table.page(); // Tidak perlu disimpan lagi

                $.ajax({
                    url: '/admin/products/' + productId + '/update-weight',
                    type: 'POST',
                    data: {
                        weight: newWeight,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swal('Success', 'Weight updated successfully', 'success');
                             // Update the cell data directly
                             let cell = table.cell($input.closest('td'));
                             cell.data(response.data); // Update the data source
                             $input.val(response.data);    // Update input value
                             cell.invalidate();

                        } else {
                            swal('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        swal('Error', 'Failed to update weight', 'error');
                    }
                });
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
                    $('#show_weight').val(res.weight)
                    $('#show_height').val(res.height)
                    $('#show_length').val(res.length)
                    $('#show_width').val(res.width)
                    $('#show_point').val(res.point)
                    $('#show_stock').val(res.stock)
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
                    $('#edit_weight').val(res.weight)
                    $('#edit_height').val(res.height)
                    $('#edit_length').val(res.length)
                    $('#edit_width').val(res.width)
                    $('#edit_stock').val(res.stock)
                    $('#edit_harga').val(res.harga)
                    $('#edit_harga').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });
                    // Calculate and set the percent value
                    let harga = parseFloat(res.harga.replace(/\./g, '')); // Remove thousand separators
                    let point = parseFloat(res.point.replace(/\./g, '')); // Remove thousand separators
                    let percent = (point / harga) * 100;
                    $('#edit_percent').val(percent); // Display with 2
                    $('#edit_point').val(point); // Display with 2
                    $('#edit_point').priceFormat({
                        prefix: '',
                        centsLimit: 0,
                        thousandsSeparator: '.'
                    });

                    tinymce.get('edit_deskripsi').setContent(res.deskripsi);

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
            formData.append('weight', formData.get('edit_weight'));
            formData.append('height', formData.get('edit_height'));
            formData.append('length', formData.get('edit_length'));
            formData.append('width', formData.get('edit_width'));
            formData.append('stock', formData.get('edit_stock'));
            formData.append('harga', formData.get('edit_harga'));
            formData.append('point', formData.get('edit_point'));
            formData.append('deskripsi', formData.get('edit_deskripsi'));
            formData.append('path_foto', formData.get('edit_foto'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_id_kategori_produk');
            formData.delete('edit_merek_produk');
            formData.delete('edit_nama_produk');
            formData.delete('edit_berat');
            formData.delete('edit_weight');
            formData.delete('edit_height');
            formData.delete('edit_length');
            formData.delete('edit_width');
            formData.delete('edit_stock');
            formData.delete('edit_harga');
            formData.delete('edit_point');
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
                        $('#modalEdit').modal('hide');
                        $('#formDataEdit').trigger('reset');

                        swal(res.message.title, res.message.content, res.message.type);
                        $('#table-1').DataTable().ajax.reload(null, false);
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
                                $('#table-1').DataTable().ajax.reload(null, false);
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

        $(document).on('input', '#harga, #percent', function() {
            let harga = $('#harga').val().replace(/\./g, '');
            let percent = $('#percent').val();

            // Validate percent is not greater than 100
            if (percent > 100) {
                $('#percent').val(100); // Set percent to 100 if it exceeds
                percent = 100; // Update the local percent variable
            }

            if (harga && percent) {
                let point = (parseInt(harga) * parseInt(percent)) / 100;
                // Format the point value to "1.000.000" format
                let formattedPoint = point.toLocaleString('id-ID');
                $('#point').val(formattedPoint);
            } else {
                $('#point').val('');
            }
        });

        $(document).on('input', '#edit_harga, #edit_percent', function() {
            let harga = $('#edit_harga').val().replace(/\./g, '');
            let percent = $('#edit_percent').val();

            // Validate percent is not greater than 100
            if (percent > 100) {
                $('#edit_percent').val(100); // Set percent to 100 if it exceeds
                percent = 100; // Update the local percent variable
            }

            if (harga && percent) {
                let point = (parseInt(harga) * parseInt(percent)) / 100;
                // Format the point value to "1.000.000" format
                let formattedPoint = point.toLocaleString('id-ID');
                $('#edit_point').val(formattedPoint);
            } else {
                $('#edit_point').val('');
            }
        });
    </script>
@endpush