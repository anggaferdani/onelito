@extends('admin.layouts.app')

@section('title', 'Current Auction')

@push('style')
    <!-- CSS Libraries -->
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
                <h1>Current Auction</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Current Auction</div>
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
                                                <th>No. Ikan</th>
                                                <th>Variety</th>
                                                <th>Breeder</th>
                                                <th>Bloodline</th>
                                                <th>Total Bid</th>
                                                <th>Harga saat ini</th>
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
        @include('admin.pages.current-auction._show')
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
            // $('#ob').priceFormat({
            //     prefix: '',
            //     centsLimit: 0,
            //     thousandsSeparator: '.'
            // });

            // $('#kb').priceFormat({
            //     prefix: '',
            //     centsLimit: 0,
            //     thousandsSeparator: '.'
            // });

            // $('#edit_ob').priceFormat({
            //     prefix: '',
            //     centsLimit: 0,
            //     thousandsSeparator: '.'
            // });

            // $('#edit_kb').priceFormat({
            //     prefix: '',
            //     centsLimit: 0,
            //     thousandsSeparator: '.'
            // });

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
                url : '{{ url("admin/current-auctions") }}',
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
                    { data : 'bid_details_count', orderable : false,searchable :false},
                    { data : 'current_price'},
                    { data : 'photo', name: 'photo.path_foto'},
                    { data : 'action' , orderable : false,searchable :false},
                ]
            });
        });

        function thousandSeparator(x) {
            var	reverse = x.toString().split('').reverse().join(''),
            ribuan 	= reverse.match(/\d{1,3}/g);
            ribuan	= ribuan.join('.').split('').reverse().join('');

            return ribuan
        }

        $(document).on('click','button#btn-show',function() {
            let id = $(this).data('id');
            let dataUrl = $(this).data('url');
            $.ajax({
                type: 'GET',
                url : `current-auctions/${id}`,
                dataType: "json",
                success: function(res) {
                    var historyBidHtml = 'Belum ada data bidding';

                    if (res.log_bids !== null) {
                        historyBidHtml = '';

                        $.each(res.log_bids, function(index, value) {

                            var name = value.log_bid.member.nama

                            var nominal = thousandSeparator(value.nominal_bid)
                            if (index === 0) {
                                historyBidHtml += `
                                <tr>
                                    <td>${name}</td>
                                    <td>Rp. ${nominal}</td>
                                    <td>${value.bid_time}</td>
                                    <td><button id="cancelBidding" data-id="${value.id_bidding_detail}" type="button" onclick="cancelLastBidding(this, 'confirm')" class="btn btn-danger mb-3">Cancel bid</button></td>
                                </tr>`
                            }
                            if (index > 0) {
                                historyBidHtml += `
                                <tr>
                                    <td>${name}</td>
                                    <td>Rp. ${nominal}</td>
                                    <td>${value.bid_time}</td>
                                    <td>&nbsp;</td>
                                </tr>`
                            }
                        });
                    }

                    $('#modalShow tbody').html(historyBidHtml);
                    $('#modalShow').modal('show');

                    // if (res.photo.path_foto) {
                        // $('#show_foto').attr('src', `/storage/${res.photo.path_foto}`)
                    // }
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
                url : `auction-products/${id}`,
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
                    $('#edit_ob').val(res.ob)
                    $('#edit_kb').val(res.kb)
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
                    $('#edit_note').summernote('code', res.note)
                    $('#edit_link_video').val(res.link_video)
                    $('#edit_extra_time').val(res.extra_time)

                    $('#edit_foto2').attr('src', ``)

                    if (res.photo.path_foto) {
                        $('#edit_foto2').attr('src', `/storage/${res.photo.path_foto}`)
                    }
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
            formData.delete('edit_ob');
            formData.delete('edit_kb');
            formData.delete('edit_note');
            formData.delete('edit_link_video');
            formData.delete('edit_foto');
            formData.delete('edit_extra_time');


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

        function cancelLastBidding(e, type)
        {
            var id = $(e).attr('data-id');
            if (type === 'confirm') {
                $('#cancelBidConfirm').modal('show');
                $('#yesCancelBidding').attr('data-id', id);
                return true
            }

            if (type === 'process') {
                $.ajax({
                    type: 'PATCH',
                    // data : formData,
                    contentType: false,
                    processData: false,
                    url : `current-auctions/${id}`,
                    beforeSend:function(){
                        // $('#btn-update').addClass("btn-progress");
                        // $(document).find('span.error-text').text('');
                    },
                    complete: function(){
                        // $('#btn-update').removeClass("btn-progress");
                    },
                    success: function(res){
                        if(res.success == true){

                            location.reload();
                            // $('#modalEdit').modal('hide');

                            // $('#formDataEdit').trigger('reset');
                            $('#example').DataTable().ajax.reload();

                            swal(res.message.title, res.message.content, res.message.type);
                        }
                    },
                    error(err){

                    }
                })

                return true;
            }

        }
    </script>


@endpush
