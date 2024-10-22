@extends('admin.layouts.app')

@section('title', 'Pembelian Store')

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
                <h1>Management Pembelian Store</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Management Pembelian Store</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>No. Pembelian</th>
                                                <th>Pembeli</th>
                                                <th>Tanggal</th>
                                                <th>Total</th>
                                                <th>Pembayaran</th>
                                                <th>Status Pengiriman</th>
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
        @include('admin.pages.order._show')
        @include('admin.pages.order._edit')
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
                url : '{{ url("admin/orders") }}',
                data : function(d) {
                    // filter here
                }
                },
                columns : [
                    { data : 'DT_RowIndex' , orderable : false,searchable :false},
                    { data : 'no_order' },
                    { data : 'latest_detail.member.nama', name: 'latestDetail.member.nama' },
                    { data : 'tanggal'},
                    { data : 'total'},
                    { data : 'pembayaran'},
                    { data : 'status'},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });
        });

        $(document).on('click','button#btn-show',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `orders/${id}`,
                dataType: "json",
                success: function(res) {
                    let sumTotal = 0;
                    let detailTable = `
                                <tbody>
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th class="">Item</th>
                                        <th class="">Kategori Barang</th>
                                        <th class="">Harga</th>
                                        <th class="">Jumlah</th>
                                        <th class="">Total</th>
                                    </tr>`;
                    let no = 1;
                    res.details.forEach(detail => {
                        sumTotal += detail.total;
                        var productable = detail.productable
                        if (detail.productable_type === 'EventFish') {
                            detailTable += `<tr>
                                        <th data-width="40">${no}</th>
                                        <th class="">Ikan Lelang No. ${productable.no_ikan}</th>
                                        <th class="">Rp. ${detail.total}</th>
                                        <th class="">${detail.jumlah_produk}</th>
                                        <th class="">Rp. ${detail.total}</th>
                                    </tr>`
                        }

                        if (detail.productable_type === 'Product') {
                            detailTable += `<tr>
                                        <th data-width="40">${no}</th>
                                        <th class="">${productable.merek_produk} ${productable.nama_produk} </th>
                                        <th class="">Barang Store</th>
                                        <th class="">${productable.harga}</th>
                                        <th class="">${detail.jumlah_produk}</th>
                                        <th class="">Rp. ${detail.total}</th>
                                    </tr>`
                        }

                        if (detail.productable_type === 'KoiStock') {
                            detailTable += `<tr>
                                        <th data-width="40">${no}</th>
                                        <th class=""> ${productable.no_ikan} | ${productable.variety} - ${productable.breeder} - ${productable.size} - ${productable.bloodline} </th>
                                        <th class="">Koi Stock</th>
                                        <th class="">${productable.harga_ikan}</th>
                                        <th class="">${detail.jumlah_produk}</th>
                                        <th class="">Rp. ${detail.total}</th>
                                    </tr>`
                        }
                        no++;
                    });

                    detailTable += `</tbody>`

                    $('#modalShow').modal('show');
                    $('.invoice-number').html(`Order #${res.no_order}`)
                    $('#send_to').html(`
                    ${res.latest_detail.member.nama}<br>
                    ${res.latest_detail.member.alamat}<br>
                    ${res.latest_detail.member.kota}, ${res.latest_detail.member.provinsi}
                    `)
                    $('#tanggal_order').html(res.tanggal)
                    $('#table_detail').html(detailTable)
                    $('#total').html(`Rp. ${sumTotal}`)
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
                url : `orders/${id}`,
                dataType: "json",
                success: function(res) {
                    document.getElementById('formEdit').action = `orders/${id}`;
                    $('#modalEdit').modal('show');
                    $('#edit_status').val(res.status)
                    $('#edit_status').trigger('change');
                },
                error:function(error) {
                    console.log(error)
                }

            })
        })

        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            formData.append('status', formData.get('edit_status'));
            formData.append('_method', 'PATCH');

            formData.delete('edit_status');


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
                text: 'Anda akan menghapus data Pembelian',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'DELETE',
                        dataType: 'JSON',
                        url: `orders/${id}`,
                        data:{
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                        },
                        success:function(response){
                            if(response.success){
                                swal('Data Pembelian berhasil dihapus', {
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
