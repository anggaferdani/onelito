@extends('admin.layouts.app')
@section('title', 'Pesanan')
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Pesanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Detail Pesanan</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <table class="table">
                            <tbody>
                              <tr>
                                <td>Status Order</td>
                                <td>
                                  @if($order->status_order == 'pending')
                                    Menunggu Pembayaran
                                  @elseif($order->status_order == 'paid')
                                    Menunggu Konfirmasi
                                  @elseif($order->status_order == 'process')
                                    Sedang Diproses
                                  @elseif($order->status_order == 'delivered')
                                    Sedang Dalam Pengiriman
                                  @elseif($order->status_order == 'done')
                                    Selesai
                                  @elseif($order->status_order == 'cancel')
                                    Dibatalkan System
                                  @elseif($order->status_order == 'cancel' && $order->dibatalkan_pembeli == 1)
                                    Dibatalkan Pembeli
                                  @endif
                                </td>
                              </tr>
                              @if($order->status_order == 'cancel' && $order->dibatalkan_pembeli == 1)
                                <tr>
                                  <td>Alasan Membatalkan Pesanan</td>
                                  <td>{{ $order->alasan_membatalkan_pesanan ?? '-' }}</td>
                                </tr>
                              @endif
                              <tr>
                                <td>No Order</td>
                                <td>{{ $order->no_order }}</td>
                              </tr>
                              <tr>
                                <td>Tanggal</td>
                                <td>{{ \Carbon\Carbon::parse($order->tanggal)->format('Y M d H:i:s') }}</td>
                              </tr>
                              <tr>
                                <td>Destination Contact Name</td>
                                <td>{{ $order->destination_contact_name }}</td>
                              </tr>
                              <tr>
                                <td>Destination Contact Phone</td>
                                <td>{{ $order->destination_contact_phone }}</td>
                              </tr>
                              <tr>
                                <td>Destination Contact Email</td>
                                <td>{{ $order->destination_contact_email }}</td>
                              </tr>
                              <tr>
                                <td>Destination Address</td>
                                <td>{{ $order->destination_address }}</td>
                              </tr>
                              <tr>
                                <td>Destination Postal Code</td>
                                <td>{{ $order->destination_postal_code }}</td>
                              </tr>
                              <tr>
                                <td>Destination Note</td>
                                <td>{{ $order->destination_note ?? '-' }}</td>
                              </tr>
                              <tr>
                                <td>Destination Coordinate Latitude</td>
                                <td>{{ $order->destination_contact_name }}</td>
                              </tr>
                              <tr>
                                <td>Destination Coordinate Longitude</td>
                                <td>{{ $order->destination_contact_name }}</td>
                              </tr>
                              <tr>
                                <td>Courier Name</td>
                                <td>{{ $order->courier_name }}</td>
                              </tr>
                              <tr>
                                <td>Courier Service Name</td>
                                <td>{{ $order->courier_service_name }}</td>
                              </tr>
                              <tr>
                                <td>Courier Price</td>
                                <td>{{ $order->courier_price }}</td>
                              </tr>
                              <tr>
                                <td>Barang</td>
                                <td>
                                  @php
                                    $totalPrice = 0;
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
                                        <div class="mb-2">{{ 'Rp. ' . number_format($detailOrderPrice, 0, '.', '.') }} x {{ $detailOrder->jumlah_produk }}</div>
                                      </div>
                                    </div>
                                  </div>
                                @endforeach
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td>Order Note</td>
                                <td>{{ $order->order_note ?? '-' }}</td>
                              </tr>
                              <tr>
                                <td>Payment Method</td>
                                <td>{{ $order->payment_method ?? '-' }}</td>
                              </tr>
                              <tr>
                                <td>Payment Channel</td>
                                <td>{{ $order->payment_channel ?? '-' }}</td>
                              </tr>
                              <tr>
                                <td>Paid Date</td>
                                <td>{{ \Carbon\Carbon::parse($order->paid_date)->format('Y M d H:i:s') }}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="card-footer">
                          <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Back</a>
                          <a href="{{ route('admin.order.invoice', $order->no_order) }}" class="btn btn-success" target="_blank">Cetak Invoice</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection