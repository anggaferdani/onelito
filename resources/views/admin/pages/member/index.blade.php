@extends('admin.layouts.app')

@section('title', 'Member')

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

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

@endpush

@section('main')
<div class="cliptext"></div>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Member</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Member</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <button class="btn btn-primary mb-2"
                            data-toggle="modal"
                            data-target="#modalCreate"
                            ><i class="fa fa-plus"></i> Tambah Member</button>

                            <div class="row col-10 col-md-4 mb-2">
                                <label for="filter_status_email">Filter Status Email</label>
                                <select name="filter_status_email" id="filter_status_email" class="form-control select2">
                                    <option value="all">Semua</option>
                                    <option value="verified">Sudah Verifikasi</option>
                                    <option value="not_verified">Belum Verifikasi</option>
                                </select>
                            </div>

                            <div class="row col-10 col-md-4 mb-2">
                                <label for="filter_status_aktif">Filter Status Aktif</label>
                                <select name="filter_status_aktif" id="filter_status_aktif" class="form-control select2">
                                    <option value="all">Semua</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>

                            <button class="btn btn-primary mb-2 mt-2"
                            id="download-excel"     
                            ><i class="fa fa-download"></i> Download Excel</button>



                                <div class="table-responsive">
                                <!-- <button class="btn btn-primary mb-2"
                                    data-toggle="modal"
                                    data-target="#modalCreate"
                                    ><i class="fa fa-plus"></i> Tambah Member</button> -->
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>No. hp</th>
                                                <th>Alamat</th>
                                                <th>Provinsi</th>
                                                <th>Kota</th>
                                                <th>Phone Number Verified</th>
                                                <th>Code OTP</th>
                                                <th>Code OTP Expired</th>
                                                <th>Status Aktif</th>
                                                <!-- <th>Kelurahan</th> -->
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
        @include('admin.pages.member._create')
        @include('admin.pages.member._show')
        @include('admin.pages.member._edit')
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
            async function editAjaxChained(source,target,slug){
                return new Promise((resolve) => {

                $.ajax({
                        type: 'GET',
                        url: '/'+slug,
                        dataType: 'html',
                        success: function(txt){
                            //no action on success, its done in the next part
                        }
                    }).done(function(data){
                        //get JSON
                        data = $.parseJSON(data);

                        //generate <options from JSON
                        var list_html = '';
                        $.each(data, function(i, item) {
                            if (target === '#kota' || target === '#edit_kota') {
                                list_html += '<option value='+data[i].city_id+'>'+data[i].city_name+'</option>'
                            }else if (target === '#kecamatan' || target === '#edit_kecamatan') {
                                list_html += '<option value='+data[i].dis_id+'>'+data[i].dis_name+'</option>';
                            }else {
                                list_html += '<option value='+data[i].subdis_id+'>'+data[i].subdis_name+'</option>';
                            }

                        });
                        //replace <select2 with new options
                        $(target).html(list_html);
                        //change placeholder text
                        // $(target).select2({placeholder: data.length +' results'});
                        resolve();
                });
            });
        }

        $(document).ready(function() {
            $('#provinsi').select2({
                dropdownParent: $('#modalCreate')
            });

            $('#kota').select2({
                dropdownParent: $('#modalCreate')
            });

            $('#kecamatan').select2({
                dropdownParent: $('#modalCreate')
            });

            $('#kelurahan').select2({
                dropdownParent: $('#modalCreate')
            });

            // $('#edit_provinsi').select2({
            //     dropdownParent: $('#modalCreate')
            // });

            // $('#edit_kota').select2({
            //     dropdownParent: $('#modalCreate')
            // });

            // $('#edit_kecamatan').select2({
            //     dropdownParent: $('#modalCreate')
            // });

            // $('#edit_kelurahan').select2({
            //     dropdownParent: $('#modalCreate')
            // });

        function ajaxChained(source,target,slug){
            $(source).on('change',function(){
            var pid = $(source+' option:selected').val(); //$(this).val();

            if (pid === null || pid === undefined) {
                return false;
            }

            $.ajax({
                    type: 'GET',
                    url: '/'+slug+pid,
                    dataType: 'html',
                    success: function(txt){
                        //no action on success, its done in the next part
                    }
                }).done(function(data){
                    //get JSON
                    data = $.parseJSON(data);

                    //generate <options from JSON
                    var list_html = '';
                    $.each(data, function(i, item) {
                        if (target === '#kota' || target === '#edit_kota') {
                            list_html += '<option value='+data[i].city_id+'>'+data[i].city_name+'</option>'
                        }else if (target === '#kecamatan' || target === '#edit_kecamatan') {
                            list_html += '<option value='+data[i].dis_id+'>'+data[i].dis_name+'</option>';
                        }else {
                            list_html += '<option value='+data[i].subdis_id+'>'+data[i].subdis_name+'</option>';
                        }

                    });
                    //replace <select2 with new options
                    $(target).html(list_html);

                    if (target === '#kota' || target === '#edit_kota') {
                        $('#kecamatan').html('');
                        $('#kelurahan').html('');
                        $('#kecamatan').select2('');
                        $('#kelurahan').select2('');
                        $('#edit_kecamatan').html('');
                        $('#edit_kelurahan').html('');
                        $('#edit_kecamatan').select2('');
                        $('#edit_kelurahan').select2('');

                    }else if (target === '#kecamatan' || target === '#edit_kecamatan') {
                        $('#kelurahan').html('');
                        $('#kelurahan').select2('');
                        $('#edit_kelurahan').html('');
                        $('#edit_kelurahan').select2('');
                    }else {
                    }
                    //change placeholder text
                    // $(target).select2({placeholder: data.length +' results'});
                });
            })
        }

        ajaxChained('#provinsi','#kota','cities?prov_id=');
        ajaxChained('#kota','#kecamatan','districts?city_id=');
        ajaxChained('#kecamatan','#kelurahan','subdistricts?dis_id=');
        // ajaxChained('#edit_provinsi','#edit_kota','cities?prov_id=');
        // ajaxChained('#edit_kota','#edit_kecamatan','districts?city_id=');
        // ajaxChained('#edit_kecamatan','#edit_kelurahan','subdistricts?dis_id=');

       var table = $('#table-1').DataTable({
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                buttons: [
                    'pageLength','excel',
                ],
                "responsive": true,
                "autoWidth" : true,
                "processing" : true,
                "serverSide" : true,
                search: {
                    return: true
                },

                ajax : {
                url : '{{ url("admin/members") }}',
                data : function(d) {
                    d.filter_status_email = $('#filter_status_email').val()
                    d.filter_status_aktif = $('#filter_status_aktif').val()
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'nama' },
                    { data : 'email'},
                    { data : 'no_hp'},
                    { data : 'alamat' , orderable : false,searchable :false},
                    { data : 'province.prov_name', orderable : false,searchable :false},
                    { data : 'city.city_name' , orderable : false,searchable :false},
                    { data : 'status_phone_number_verification', orderable: false, searchable :false},
                    { data : 'verification_code', orderable: false, searchable :false},
                    { data : 'verification_code_expires_at', orderable: false, searchable :false},
                    { data : 'status_aktif', orderable: false, searchable :false},
                    // { data : 'kecamatan' , orderable : false,searchable :false},
                    // { data : 'kelurahan' , orderable : false,searchable :false},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });

            $('#filter_status_email').change(function() {
                table.draw();
            });

            $('#filter_status_aktif').change(function() {
                table.draw();
            });
        });



        $(document).on('click','button#btn-show',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `members/${id}`,
                dataType: "json",
                success: function(res) {
                    $('#modalShow').modal('show');
                    $('#show_nama').val(res.nama)
                    $('#show_email').val(res.email)
                    $('#show_no_hp').val(res.no_hp)
                    $('#show_alamat').val(res.alamat)

                    var province = "";
                    if (res.province !== null) {
                        province = res.province.prov_name;
                    }

                    $('#show_provinsi').val(province)
                    var kota = "";
                    if (res.city !== null) {
                        kota = res.city.city_name;
                    }
                    $('#show_kota').val(kota)

                    var kecamatan = "";
                    if (res.district !== null) {
                        kecamatan = res.district.dis_name;
                    }

                    $('#show_kecamatan').val(kecamatan)

                    var kelurahan = "";
                    if (res.subdistrict !== null) {
                        kelurahan = res.subdistrict.subdis_name;
                    }

                    $('#show_kelurahan').val(kelurahan)
                    $('#show_kode_pos').val(res.kode_pos)
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
                url : `members/${id}`,
                dataType: "json",
                success:async function(res) {
                    // $('#edit_provinsi').val(null).trigger('change')
                    // $('#edit_kota').val(null).trigger('change')
                    // $('#edit_kecamatan').val(null).trigger('change')
                    // $('#edit_kelurahan').val(null).trigger('change')

                    // if (res.provinsi !== null) {
                    //     await editAjaxChained('#edit_provinsi','#edit_kota',`cities?prov_id=${res.provinsi}`);
                    // }

                    document.getElementById('formEdit').action = `members/${id}`;
                    $('#modalEdit').modal('show');
                    $('#edit_nama').val(res.nama)
                    $('#edit_email').val(res.email)
                    $('#edit_no_hp').val(res.no_hp)
                    $('#edit_alamat').val(res.alamat)

                    // if (res.provinsi !== null) {
                    //     $('#edit_provinsi').val(res.provinsi).trigger('change')
                    // }

                    // if (res.kota !== null) {
                    //     await editAjaxChained('#edit_kota','#edit_kecamatan',`districts?city_id=${res.kota}`);
                    //     $('#edit_kota').val(res.kota).trigger('change')
                    // }

                    // if (res.kecamatan !== null) {
                    //     $('#edit_kecamatan').val(res.kecamatan).trigger('change')
                    // }

                    // if (res.kelurahan !== null) {
                    //     $('#edit_kelurahan').val(res.kelurahan).trigger('change')
                    // }

                    $('#edit_kode_pos').val(res.kode_pos)
                    $('#edit_status_aktif').val(res.status_aktif).trigger('change')

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
                email: formData.get('edit_email'),
                no_hp: formData.get('edit_no_hp'),
                alamat: formData.get('edit_alamat'),
                // provinsi: formData.get('edit_provinsi'),
                // kota: formData.get('edit_kota'),
                // kecamatan: formData.get('edit_kecamatan'),
                // kelurahan: formData.get('edit_kelurahan'),
                kode_pos: formData.get('edit_kode_pos'),
                status_aktif: formData.get('edit_status_aktif')
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
                text: 'Anda akan menghapus data peserta',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `members/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data peserta berhasil dihapus', {
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

        $(document).on('click','button#btn-password',function() {
            let id = $(this).data('id');

            swal({
                title: 'Anda Yakin?',
                text: `Anda akan mereset password akun peserta menjadi
                'ikankoi123'`,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'PATCH',
                        dataType: 'JSON',
                        url: `members/${id}?action=reset-password`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Password berhasil direset', {
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

        $(document).on('click','button#btn-send-otp',function() {
            var url = $(this).data('url');

            swal({
                title: 'Verifikasi OTP WhatsApp',
                text: 'Kirim ulang OTP via WhatsApp?',
                icon: 'info',
                buttons: true,
            })
            .then((willSend) => {
                if (willSend) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: url,
                        success: function(response) {
                            swal({
                                title: response.success ? 'Berhasil!' : 'Gagal!',
                                text: response.message,
                                icon: response.success ? 'success' : 'error',
                            });
                        },
                        error: function(xhr) {
                            swal({
                                title: 'Error Server',
                                text: xhr.responseJSON?.message ?? 'Terjadi kesalahan tak terduga.',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click','button#btn-copy-url-verif', async function() {
                var id = $(this).data('id');
                let url = await $(this).data('email-url');

                // url.select();
                // url.setSelectionRange(0, 99999);

                // console.log({url})

                // navigator.clipboard.write(`${url}`)

                const type = "text/plain";
                const blob = new Blob([url], { type });
                const data = [new ClipboardItem({ [type]: blob })];

                navigator.clipboard.write(data).then(
                    () => {
                    /* success */
                    alert("Copied Verification Url");

                    },
                    () => {
                    /* failure */
                    }
                );


                // navigator.clipboard.writeText($(this).data('email-url'));

        });

        $(document).on('click','button#download-excel',function() {
            var email = $('#filter_status_email').val();
            var status = $('#filter_status_aktif').val();

            var url = new URL(window.location.protocol + "//" +window.location.host+"/admin/members/excels");

            url.searchParams.append("filter_status_email", email);
            url.searchParams.append("filter_status_aktif", status);
            console.log(url);
            var link=document.createElement('a');
            link.href = url.href;
            link.click();
        });
    </script>


@endpush
