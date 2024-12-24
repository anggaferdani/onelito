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
        <div id="pengiriman">
          <div>
            <div class="d-flex justify-content-between">
              <div class="fw-bold">(updated_at tanggal)</div>
              <div>(updated_at waktu)</div>
            </div>
            <div>(note)</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach