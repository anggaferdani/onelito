@foreach($orders as $order)
<div class="modal fade" id="lainnya{{ $order->id_order }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Detail Transaksi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="text-muted">No. Invoice</div>
          </div>
          <div class="col-md-8 text-md-end">
            <div class="">
              <a href="{{ route('order.invoice', $order->no_order) }}" class="fw-bold text-success" target="_blank">{{ $order->no_order }} (Lihat)</a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="text-muted">Tanggal Order</div>
          </div>
          <div class="col-md-8 text-md-end">
            <div class="">{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y, H:i') }}</div>  
          </div>
        </div>
        <hr>
        <div class="mb-3 fw-bold">Detail Produk</div>
        <div class="row align-items-center g-2">
          @php
            $totalPrice = 0;
            $totalCoins = 0;
            $detailCount = $order->details->count();
          @endphp
          @foreach($order->details as $detailOrder)
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
                $totalCoins += $productable->point * $detailOrder->quantity;
            @endphp
            <div class="col-md-12">
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
                  <div class="">{{ 'Rp. ' . number_format($detailOrderPrice, 0, '.', '.') }} x {{ $detailOrder->jumlah_produk }}</div>
                  <div>
                    @if($productable->point > 0)
                    <span class="text-success small">+ Total {{ 'Rp. ' . number_format($totalCoins, 0, '.', '.') }} Onelito Coins</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <hr>
        <div class="mb-3 fw-bold">Info Pengiriman</div>
        <table style="width: 100%;">
          <tr>
            <td class="align-top" style="width: 20%;">No Resi</td>
            <td class="align-top">{{ $order->waybill_id }}</td>
          </tr>
          <tr>
            <td class="align-top" style="width: 20%;">Kurir</td>
            <td class="align-top">{{ $order->courier_name }}</td>
          </tr>
          <tr>
            <td class="align-top" style="width: 20%;">Alamat</td>
            <td class="align-top">
              <div class="fw-bold">{{ $order->destination_contact_name }}</div>
              <div>{{ $order->destination_contact_phone }}</div>
              <div>{{ $order->destination_address }} @if($order->destination_note) ({{ $order->destination_note }}) @endif</div>
              <div>{{ $order->destination_postal_code }}</div>
            </td>
          </tr>
        </table>
        <hr>
        <div class="mb-3 fw-bold">Rincian Pembayaran</div>
        <table style="width: 100%;">
          <tr>
            <td class="align-top" style="width: 40%;">Metode Pembayaran</td>
            <td class="align-top">{{ $order->payment_channel }}</td>
          </tr>
          <tr>
            <td class="align-top" style="width: 40%;">Total Harga Barang</td>
            <td class="align-top">{{ 'Rp. ' . number_format($order->total_harga_barang, 0, '.', '.') }}</td>
          </tr>
          <tr>
            <td class="align-top" style="width: 40%;">Total Ongkos Kirim</td>
            <td class="align-top">{{ 'Rp. ' . number_format($order->courier_price, 0, '.', '.') }}</td>
          </tr>
          <tr>
            <td class="align-top" style="width: 40%;">Total Belanja</td>
            <td class="align-top">{{ 'Rp. ' . number_format($order->jumlah_total, 0, '.', '.') }}</td>
          </tr>
          <tr>
            <td class="align-top fw-bold" style="width: 40%;">Total Tagihan</td>
            <td class="align-top">
              <div class="fw-bold">{{ 'Rp. ' . number_format($order->total_tagihan, 0, '.', '.') }}</div>
              @if($order->coin_yang_digunakan > 0) <div class="text-success small">(Potongan Onelito Coins {{ 'Rp. ' . number_format($order->coin_yang_digunakan, 0, '.', '.') }})</div> @endif
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach