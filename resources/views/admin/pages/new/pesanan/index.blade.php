@extends('admin.layouts.app')
@section('title', 'Pesanan')
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Management Pesanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Management Pesanan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <div class="float-left">
                          </div>
                          <div class="float-right">
                            </div>

                          <div class="clearfix mb-3"></div>

                          <div class="table-responsive">
                            <table class="table table-bordered" id="orders-table">
                              <thead>
                                <tr>
                                  <th>No.</th>
                                  <th>No Order</th>
                                  <th>Tanggal</th>
                                  <th>Nama Penerima</th>
                                  <th>Total Tagihan</th>
                                  <th>Opsi Pengiriman</th>
                                  <th>Status</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@foreach($orders as $order)
<div class="modal fade" id="batalkan-pesanan{{ $order->id_order }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Batalkan Pesanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <form action="{{ route('admin.order.cancel', $order->no_order) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Kenapa membatalkan pesanan?</label>
            <textarea style="height: 150px;" class="form-control" name="alasan_membatalkan_pesanan" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Batalkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="lacak-pengiriman{{ $order->id_order }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="">Lacak Pengiriman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="text-muted">No. Invoice</div>
          </div>
          <div class="col-md-8 text-md-end">
            <div class="fw-bold text-success">{{ $order->no_order }}</div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="text-muted">Tanggal Pembelian</div>
          </div>
          <div class="col-md-8 text-md-end">
            <div class="">{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y, H:i') }}</div>
          </div>
        </div>
        <hr>
        <div class="mb-3 fw-bold">Info Pengiriman</div>
        <div id="pengirimanContainer">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
<script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('library/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('library/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>

<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.all.js" integrity="sha512-AINSNy+d2WG9ts1uJvi8LZS42S8DT52ceWey5shLQ9ArCmIFVi84nXNrvWyJ6bJ+qIb1MnXR46+A4ic/AUcizQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">

<script>
$(document).ready(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.pesanan.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'no_order', name: 'no_order'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'destination_contact_name', name: 'destination_contact_name'},
            {data: 'total_tagihan', name: 'total_tagihan'},
            {data: 'opsi_pengiriman', name: 'opsi_pengiriman'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

$('#orders-table').on('click', '.lacak-pengiriman-btn', function() {
    const trackingId = $(this).data('tracking-id');
    const id_order = $(this).data('id_order');
    const modalBody = $('#lacak-pengiriman' + id_order).find('.modal-body #pengirimanContainer');
    const BITESHIP_API_KEY = $('meta[name="biteship-api-key"]').attr('content');

    $.ajax({
        url: `https://api.biteship.com/v1/trackings/${trackingId}`,
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${BITESHIP_API_KEY}`,
            'Content-Type': 'application/json'
        },
        success: function(data) {
            console.log(data);
            if (data && data && data.success === true) {
                const trackingUpdates = data.history;
                let content = '';

                trackingUpdates.forEach(function(update) {
                    const updatedAt = new Date(update.updated_at);
                    const dateFormatted = updatedAt.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                    const timeFormatted = updatedAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                    let statusMessage = '';

                    switch (update.status) {
                        case 'confirmed':
                            statusMessage = 'Order has been confirmed. Locating nearest driver to pick up.';
                            break;
                        case 'allocated':
                            statusMessage = 'Courier has been allocated. Waiting to pick up.';
                            break;
                        case 'pickingUp':
                            statusMessage = 'Courier is on the way to pick up item.';
                            break;
                        case 'picked':
                            statusMessage = 'Item has been picked and ready to be shipped.';
                            break;
                        case 'droppingOff':
                            statusMessage = 'Item is on the way to customer.';
                            break;
                        case 'returnInTransit':
                            statusMessage = 'Order is on the way back to the origin.';
                            break;
                        case 'onHold':
                            statusMessage = 'Your shipment is on hold at the moment. We\'ll ship your item after it\'s resolved.';
                            break;
                        case 'delivered':
                            statusMessage = 'Item has been delivered.';
                            break;
                        case 'rejected':
                            statusMessage = 'Your shipment has been rejected. Please contact Biteship for more information.';
                            break;
                        case 'courierNotFound':
                            statusMessage = 'Your shipment is canceled because there\'s no courier available at the moment.';
                            break;
                        case 'returned':
                            statusMessage = 'Order successfully returned.';
                            break;
                        case 'cancelled':
                            statusMessage = 'Order is cancelled.';
                            break;
                        case 'disposed':
                            statusMessage = 'Order successfully disposed.';
                            break;
                        default:
                            statusMessage = 'Status not available.';
                            break;
                    }

                    content += `
                        <div id="pengiriman" class="mb-1">
                            <div class="d-flex justify-content-between mb-1">
                                <div>${dateFormatted}</div>
                                <div>${timeFormatted}</div>
                            </div>
                            <div class="fw-bold text-success mb-1">${statusMessage}</div>
                            <div class="small">${update.note}</div>
                        </div>
                        <hr>
                    `;
                });

                modalBody.html(content);
            } else {
                modalBody.html('<div class=""></div>');
            }
        },
        error: function(response) {
            console.log(response);
            modalBody.html('<div class=""></div>');
        }
    });
});

$(document).on('click', '.delete', function(event){
    event.preventDefault();

    var url = $(this).attr('href');

    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure you want to delete this item?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it",
        closeOnConfirm: false
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = url;
            Swal.fire(
                'Deleted',
                'You have successfully deleted',
                'success',
            );
        }
    });
});


$(document).on('click', '.delete2', function(event) {
    event.preventDefault();

    var url = $(this).attr('href');
    var title = $(this).data('title');
    var text = $(this).data('text');
    var confirmButtonText = $(this).data('confirm-button');
    var confirmButtonClass = $(this).data('confirm-button-class');

    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonClass: confirmButtonClass,
        confirmButtonText: confirmButtonText,
        closeOnConfirm: false,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
            Swal.fire(
                `Berhasil ${title}`,
                `Tindakan "${title}" berhasil dilakukan.`,
                'success'
            );
        }
    });
  });

$('.select2').select2();
$('.select3').select2({tags: true});

</script>
@endpush