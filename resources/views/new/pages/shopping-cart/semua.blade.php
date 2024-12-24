@extends('new.templates.shopping-cart')
@section('title', 'Semua')
@section('content')
@if(Session::get('success'))
  <div class="alert alert-important alert-success" role="alert">
    {{ Session::get('success') }}
  </div>
@endif
@if(Session::get('error'))
  <div class="alert alert-important alert-danger" role="alert">
    {{ Session::get('error') }}
  </div>
@endif
<div class="row g-3">
  @foreach($orders as $order)
  <div class="col-md-12">
    <div class="row align-items-center g-2">
      <div class="col-md-6"><img src="{{ asset('img/logo-onelito.png') }}" width="150" alt=""></div>
      <div class="col-md-6 text-md-end">
        @if($order->status_order == 'pending')
          <div class="text-danger">Menunggu Pembayaran</div>
        @elseif($order->status_order == 'paid')
          <div class="text-danger">Menunggu Konfirmasi</div>
        @elseif($order->status_order == 'process')
          <div class="text-danger">Sedang Diproses</div>
        @elseif($order->status_order == 'delivered')
          <div class="text-danger">Sedang Dalam Pengiriman</div>
        @elseif($order->status_order == 'done')
          <div><span class="text-success">Pesanan telah sampai ditujuan</span> | <span class="text-danger">Selesai</span></div>
        @elseif($order->status_order == 'cancel')
          @if($order->alasan_membatalkan_pesanan)
          <div class="text-danger">Dibatalkan Pembeli</div>
          @else
          <div class="text-danger">Dibatalkan System</div>
          @endif
        @endif
      </div>
    </div>
    <hr>
    <div class="row align-items-center g-2">
      @php
        $totalPrice = 0;
        $detailCount = $order->details->count();
      @endphp
      @foreach($order->details->take(1) as $detailOrder)
        @php
            $productable = $detailOrder->productable;

            if ($detailOrder->productable_type === 'Product') {
                $detailOrderPrice = $productable->harga;
                if ($detailOrder->productable->photo !== null) {
                    $detailOrderPhoto = url('storage') . '/' . $detailOrder->productable->photo->path_foto;
                }
            } elseif ($detailOrder->productable_type === 'KoiStock') {
                $detailOrderPrice = $productable->harga_ikan;
                $detailOrderPhoto = url('storage') . '/' . $productable->foto_ikan;
            }
            
            $totalPrice += $detailOrderPrice * $detailOrder->jumlah_produk;
        @endphp
        <div class="col-md-6">
          <div class="row g-2">
            <div class="col-3 col-md-3"><img src="{{ $detailOrderPhoto }}" alt="" class="img-fluid border p-2 rounded"></div>
            <div class="col-9 col-md-9">
              <div class="fw-bold">
                @if($detailOrder->productable_type === 'KoiStock')
                  {{ $productable->variety }} | {{ $productable->breeder }} | {{ $productable->bloodline }} | {{ $productable->size }}
                @elseif($detailOrder->productable_type === 'Product')
                  {{ $productable->merek_produk }} {{ $productable->nama_produk }}
                @endif
              </div>
              <div class="mb-2">{{ 'Rp. ' . number_format($detailOrderPrice, 0, '.', '.') }} x {{ $detailOrder->jumlah_produk }}</div>
            </div>
          </div>
        </div>
      @endforeach
      @if($detailCount > 1)
        <div class="col-md-6 text-md-end"><div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#lainnya{{ $order->id_order }}">+{{ $detailCount - 1 }} Produk Lainnya</div></div>
      @endif
    </div>
    <hr>
    <div class="d-flex justify-content-md-end align-items-center gap-1 mb-3"><div>Total Pesanan :</div><div class="text-danger fw-bold fs-5">{{ 'Rp. ' . number_format($order->total_tagihan, 0, ',', '.') }}</div></div>
    <div class="row align-items-center g-3">
      <div class="col-md-4">
        @if($order->status_order == 'pending')
          <div>Bayar sebelum {{ \Carbon\Carbon::parse($order->expiry_date)->format('d F Y, H:i') }}</div>
        @endif
      </div>
      <div class="col-md-8">
        <div class="d-md-flex gap-1 justify-content-end">
          @if($order->status_order == 'pending')
            <a href="{{ $order->invoice_url }}" class="btn btn-success mb-1" target="_blank">Bayar Sekarang</a>
            <a href="https://wa.me/0811972857" target="_blank" class="btn btn-outline-success mb-1">Hubungi Penjual</a>
            <button class="btn btn-outline-success mb-1" data-bs-toggle="modal" data-bs-target="#lainnya{{ $order->id_order }}">Detail Transaksi</button>
            <button class="btn btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#batalkan-pesanan{{ $order->id_order }}">Batal Pesanan</button>
          @elseif($order->status_order == 'paid')
            <a href="https://wa.me/0811972857" target="_blank" class="btn btn-outline-success mb-1">Hubungi Penjual</a>
            <button class="btn btn-outline-success mb-1" data-bs-toggle="modal" data-bs-target="#lainnya{{ $order->id_order }}">Detail Transaksi</button>
          @elseif($order->status_order == 'process')
            <a href="https://wa.me/0811972857" target="_blank" class="btn btn-outline-success mb-1">Hubungi Penjual</a>
            <button class="btn btn-outline-success mb-1" data-bs-toggle="modal" data-bs-target="#lainnya{{ $order->id_order }}">Detail Transaksi</button>
          @elseif($order->status_order == 'delivered')
            <a href="https://wa.me/0811972857" target="_blank" class="btn btn-outline-success mb-1">Hubungi Penjual</a>
            <button class="btn btn-outline-success mb-1" data-bs-toggle="modal" data-bs-target="#lainnya{{ $order->id_order }}">Detail Transaksi</button>
            <button class="btn btn-outline-success mb-1" data-bs-toggle="modal" data-bs-target="#lacak-pengiriman{{ $order->id_order }}">Lacak Pengiriman</button>
            {{-- @if($order->tracking_url )<a href="{{ $order->tracking_url }}" class="btn btn-outline-success mb-1" target="_blank">Lacak Pengiriman</a> @endif --}}
          @endif
        </div>
      </div>
    </div>
    <hr>
  </div>
  @endforeach
</div>

@include('new.pages.shopping-cart.modal')
@foreach($orders as $order)
<div class="modal fade" id="lacak-pengiriman{{ $order->id_order }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Lacak Pengiriman</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="text-muted">No. Invoice</div>
          </div>
          <div class="col-md-8 text-md-end">
            <div class="text-success"></div>  
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach
@foreach($orders as $order)
<div class="modal fade" id="batalkan-pesanan{{ $order->id_order }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Batalkan Pesanan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('order.cancel', $order->no_order) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="">
            <label class="form-label">Kenapa membatalkan pesanan? <span class="text-danger">*optional</span></label>
            <textarea class="form-control" rows="3" name="alasan_membatalkan_pesanan" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    @foreach($orders as $order)
      $('#lacak-pengiriman{{ $order->id_order }}').on('show.bs.modal', function () {
        const trackingId = '{{ $order->tracking_id }}';
        const modalBody = $(this).find('.modal-body #pengirimanContainer');
        
        const BITESHIP_API_KEY = $('meta[name="biteship-api-key"]').attr('content');

        $.ajax({
          url: `https://api.biteship.com/v1/trackings/${trackingId}`,
          method: 'GET',
          headers: {
            'Authorization': `Bearer ${BITESHIP_API_KEY}`,
            'Content-Type': 'application/json'
          },
          success: function(data) {
            if (data && data && data.success === true) {
              const trackingUpdates = data.history;
              let content = '';
              
              trackingUpdates.forEach(function(update) {
                const updatedAt = new Date(update.updated_at);
                const dateFormatted = updatedAt.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                const timeFormatted = updatedAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                let statusMessage = '';
                let isBold = update.status === 'picked' ? 'fw-bold' : 'fw-bold text-secondary';

                switch(update.status) {
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
                    <div class="d-flex justify-content-between mb-2">
                      <div>${dateFormatted}</div>
                      <div>${timeFormatted}</div>
                    </div>
                    <div class="${isBold} mb-2">${statusMessage}</div>
                    <div class="text-secondary small">${update.note}</div>
                  </div>
                  <hr>
                `;
              });

              modalBody.html(content);
            } else {
              modalBody.html('<div class=""></div>');
            }
          },
          error: function() {
            modalBody.html('<div class=""></div>');
          }
        });
      });
    @endforeach
  });
</script>
@endpush