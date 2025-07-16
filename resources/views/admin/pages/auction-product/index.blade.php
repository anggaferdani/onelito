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
    $(document).ready(function() {
        // Inisialisasi priceFormat untuk modal create
        $('#ob, #kb').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });

        // Inisialisasi priceFormat untuk modal edit (cukup sekali di sini)
        $('#edit_ob, #edit_kb').priceFormat({
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
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'no_ikan' },
                { data: 'variety' },
                { data: 'breeder' },
                { data: 'bloodline' },
                { data: 'sex' },
                { data: 'dob' },
                { data: 'size' },
                { data: 'extra_time' },
                { data: 'photo', name: 'photo.path_foto' },
                { data: 'action', orderable: false, searchable: false },
            ]
        });
    });

    // Handler untuk modal Show
    $(document).on('click', 'button#btn-show', function() {
        let id = $(this).data('id');
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
                // Pastikan currency ada sebelum mengakses symbol
                if (res.currency) {
                    $('#show_ob').val(res.currency.symbol + ' ' + res.ob)
                    $('#show_kb').val(res.currency.symbol + ' ' + res.kb)
                }
                $('#show_note').html(res.note)
                $('#show_link_video').val(res.link_video)
                $('#show_extra_time').val(res.extra_time)
                
                // Reset gambar dulu
                $('#show_foto').attr('src', ``); 
                // Cek jika photo dan path_foto ada
                if (res.photo && res.photo.path_foto) {
                    $('#show_foto').attr('src', `/storage/${res.photo.path_foto}`)
                }
            },
            error: function(error) {
                console.log(error);
                swal('Error', 'Gagal memuat detail data.', 'error');
            }
        })
    });

    // Handler untuk modal Edit
    $(document).on('click', 'button#btn-edit', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: `auction-products/${id}`,
            dataType: "json",
            success: function(res) {
                // 1. ISI DATA DULU
                document.getElementById('formEdit').action = `auction-products/${id}`;
                $('#edit_no_ikan').val(res.no_ikan);
                $('#edit_variety').val(res.variety);
                $('#edit_breeder').val(res.breeder);
                $('#edit_bloodline').val(res.bloodline);
                $('#edit_sex').html(
                    `<option value="Male" ${res.sex === 'Male' ? 'selected' : ''}>Male</option>
                     <option value="Female" ${res.sex === 'Female' ? 'selected' : ''}>Female</option>
                     <option value="Unknown" ${res.sex === 'Unknown' ? 'selected' : ''}>Unknown</option>`
                );
                $('#edit_dob').val(res.dob);
                $('#edit_size').val(res.size);
                
                // Safety check untuk currency
                if (res.currency) {
                    $('#edit_currency_id').val(res.currency.id).trigger('change');
                }

                $('#edit_ob').val(res.ob);
                $('#edit_kb').val(res.kb);
                
                // Inisialisasi ulang konten TinyMCE
                if (tinymce.get('edit_note')) {
                     tinymce.get('edit_note').setContent(res.note || '');
                }

                $('#edit_link_video').val(res.link_video);
                $('#edit_extra_time').val(res.extra_time);

                // Safety check untuk foto
                $('#edit_foto2').attr('src', ''); // Hapus gambar lama
                if (res.photo && res.photo.path_foto) {
                    $('#edit_foto2').attr('src', `/storage/${res.photo.path_foto}`);
                }

                // 2. TAMPILKAN MODAL SETELAH SEMUA DATA SIAP
                $('#modalEdit').modal('show');
            },
            error: function(error) {
                console.log(error);
                // Beri tahu pengguna jika gagal memuat data
                swal('Gagal Memuat Data', 'Tidak dapat mengambil data ikan. Silakan coba lagi.', 'error');
            }
        });
    });
    
    // **[TAMBAHAN PENTING]** Reset form setiap kali modal edit ditutup
    $('#modalEdit').on('hidden.bs.modal', function () {
        $('#formEdit').reset(); // Reset form standar
        $('#formEdit').attr('action', '#'); // Hapus action url lama
        $('#edit_currency_id').val(null).trigger('change'); // Reset Select2
        
        // Hapus pesan error validasi jika ada
        $('.error-text').text('');

        // Reset TinyMCE
        if (tinymce.get('edit_note')) {
            tinymce.get('edit_note').setContent('');
        }
        
        // Reset preview gambar
        $('#edit_foto2').attr('src', '');
    });


    // Handler untuk Submit Form Edit
    $('#formEdit').submit(function(e) {
        e.preventDefault();
        tinymce.triggerSave();
        let formData = new FormData(this);
        formData.append('_method', 'PATCH');
        
        // Tidak perlu append dan delete manual, karena nama input sudah sesuai
        // Cukup pastikan atribut 'name' pada input di form _edit.blade.php sudah benar

        $.ajax({
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            url: $(this).attr('action'),
            beforeSend: function() {
                $('#btn-update').addClass("btn-progress");
                // Hapus semua pesan error sebelumnya
                $(document).find('span.error-text').text('');
            },
            complete: function() {
                $('#btn-update').removeClass("btn-progress");
            },
            success: function(res) {
                if (res.success == true) {
                    $('#modalEdit').modal('hide');
                    // Form akan direset oleh event 'hidden.bs.modal'
                    $('#table-1').DataTable().ajax.reload(null, false);
                    swal(res.message.title, res.message.content, res.message.type);
                }
            },
            error(err) {
                // Menampilkan pesan error validasi dari server
                $.each(err.responseJSON.errors, function(prefix, val) {
                    $('.' + prefix + '_error_edit').text(val);
                })
            }
        })
    });

    // Handler untuk Tombol Delete
    $(document).on('click', 'button#btn-delete', function() {
        let id = $(this).data('id');
        swal({
            title: 'Anda Yakin?',
            text: 'Anda akan menghapus data barang lelang secara permanen!',
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
                            swal('Berhasil!', 'Data barang lelang berhasil dihapus.', 'success');
                            $('#table-1').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function(err) {
                        swal({
                            icon: 'error',
                            title: 'Terjadi kesalahan',
                            text: err.responseJSON ? err.responseJSON.message : 'Tidak dapat menghapus data.'
                        })
                    }
                });
            }
        });
    });
</script>
@endpush
