@extends('admin.layouts.app')

@section('title', 'Pemenang Lelang')

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
        href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Pemenang Lelang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Pemenang Lelang</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <!-- <button class="btn btn-primary mb-3"
                            data-toggle="modal"
                            data-target="#modalCreate"
                            ><i class="fa fa-plus"></i> Tambah Pemenang Lelang</button> -->
                            <button class="btn btn-primary mb-2 mt-2"
                            id="download-excel"     
                            ><i class="fa fa-download"></i> Download Excel</button>

                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Pemenang</th>
                                                <!-- <th>Ikan</th> -->
                                                <th>Alamat</th>
                                                <th>Tinggal</th>
                                                <th>Kategori Event</th>
                                                <th>Tgl. Mulai</th>
                                                <!-- <th>Tgl. Akhir</th> -->
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
        @include('admin.pages.auction-winner._create')
        @include('admin.pages.auction-winner._show')
        @include('admin.pages.auction-winner._edit')
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

    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        let auctionProducts = @json($auctionProducts)

        $(document).ready(function() {
            function thousandSeparator(x) {
                // return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
                var	reverse = x.toString().split('').reverse().join(''),
                ribuan 	= reverse.match(/\d{1,3}/g);
                ribuan	= ribuan.join('.').split('').reverse().join('');

                return ribuan
            }

            $('#table-1').DataTable({
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
                url : '{{ url("admin/auction-winners") }}',
                data : function(d) {
                    // filter here
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'member.nama', name: 'member.nama' },
                    { data : 'member.alamat', name: 'member.alamat'},
                    { data : 'member.city.city_name' ,name: 'member.city.city_name',  defaultContent: ''},
                    { data : 'event.kategori_event'},
                    { data : 'event.tgl_mulai'},
                    // { data : 'event.tgl_akhir'},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });

            $(document).on('change','select#auction_product',function() {
                console.log('clicked');
                var id = $('#auction_product').val();

                var data = $.map(auctionProducts[id].bids, function(item) {
                    var name = item.member.nama;
                    var nominalBid = thousandSeparator(item.nominal_bid)

                    return {id: item.id_bidding, text: `${name} | Rp. ${nominalBid}`};
                });

                $("select#id_bidding").html('');
                $("select#id_bidding").select2({
                    data: data
                })
            })

            $(document).on('click','button#btn-show',function() {
            let idEvent = $(this).data('event');
            let idPeserta = $(this).data('peserta');
            // let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `auction-winners-info?id_peserta=${idPeserta}&id_event=${idEvent}`,
                dataType: "json",
                success: function(res) {
                    let sumTotal = 0;
                    let detailTable = `
                                <tbody>
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Foto ikan</th>
                                        <th class="">Item</th>
                                        <th class="">Total</th>
                                    </tr>`;
                    let no = 1;
                    res.details.forEach(detail => {
                        console.log({detail})
                        sumTotal += detail.bidding.nominal_bid;
                        var numberTotal = thousandSeparator(detail.bidding.nominal_bid);
                        var img = '';

                        if (detail.bidding.event_fish.photo !== null) {
                            img = `/storage/${detail.bidding.event_fish.photo.path_foto}`;
                        }

                        detailTable += `<tr>
                                    <th data-width="40">${no}</th>
                                    <th><img style="width: 100px;
                                    height: 145px;
                                    " src="${img}"></th>
                                    <th class="">Ikan Lelang No. ${detail.bidding.event_fish.no_ikan}</th>
                                    <th class="">Rp. ${numberTotal}</th>
                                </tr>`
                        no++;
                    });

                    var city = '';
                    var prov = '';

                    if (res.member.city !== null) {
                        city = res.member.city.city_name;
                    }

                    if (res.member.province !== null) {
                        prov = res.member.province.prov_name;
                    }

                    detailTable += `</tbody>`
                    sumTotal = thousandSeparator(sumTotal);
                    $('#send_to').html(`
                    ${res.member.nama}<br>
                    No Telp. ${res.member.no_hp}<br>
                    ${res.member.alamat}<br>
                    ${city}, ${prov}
                    `)
                    $('#table_detail').html(detailTable)
                    $('#total').html(`Rp. ${sumTotal}`)
                    $('#modalShow').modal('show');
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })
        });

        $(document).on('click','button#btn-edit',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `auction-winners/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `auction-winners/${id}`;
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

        $(document).on('click','button#btn-completed',function() {
                var id_peserta = $(this).data('peserta');
                var id_event = $(this).data('event');
                var status_pembayaran = $(this).data('status');
                var url = "{{ url('admin/auction-winners-update') }}";

                var textBody = 'Update status transaksi menjadi Selesai';

                if (status_pembayaran == 1) {
                    textBody = 'Update status transaksi menjadi Belum Selesai'
                }
                swal({
                    title: 'Update',
                    text: textBody,
                    icon: 'info',
                    buttons: true,
                    dangerMode: false,
                    })
                    .then((willSend) => {
                    if (willSend) {
                        $.ajax({
                            type:'PATCH',
                            dataType: 'JSON',
                            url: url,
                            data:{
                                "_token": $('meta[name="csrf-token"]').attr('content'),
                                "id_peserta": id_peserta,
                                "id_event": id_event,
                                "status": status_pembayaran,
                            },
                            success:function(response){
                                if(response.success){
                                    swal('Data berhasil di update', {
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
                text: 'Anda akan menghapus data Pemenang Lelang',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `auction-winners/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data Pemenang Lelang berhasil dihapus', {
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

        $(document).on('click','button#download-excel',function() {
            var url = new URL(window.location.protocol + "//" +window.location.host+"/admin/auction-winners/excels");

            var link=document.createElement('a');
            link.href = url.href;
            link.click();
        });
    </script>


@endpush
