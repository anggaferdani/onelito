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
                      <div class="card-footer d-flex justify-content-between">
                        <div>
                        </div>
                        <div>
                          <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                          @if($order->status_order == 'pending')
                          @elseif($order->status_order == 'paid')
                            <a 
                              href="{{ route('admin.pesanan.process', $order->no_order) }}" 
                              class="btn btn-warning delete2" 
                              data-title="Proses Pesanan" 
                              data-text="Apakah Anda yakin ingin memproses pesanan ini?" 
                              data-confirm-button="Ya, proses sekarang"
                              data-confirm-button-class="btn btn-warning">
                              Proses Pesanan
                            </a>
                          @elseif($order->status_order == 'process')
                            <a 
                              href="{{ route('admin.pesanan.kirim', $order->no_order) }}" 
                              class="btn btn-primary delete2" 
                              data-title="Kirim Pesanan" 
                              data-text="Apakah Anda yakin ingin mengirim pesanan ini?" 
                              data-confirm-button="Ya, kirim sekarang"
                              data-confirm-button-class="btn btn-primary">
                              Kirim Pesanan
                            </a>
                          @else
                            @if($order->opsi_pengiriman == 'otomatis')
                            <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#lacak-pengiriman{{ $order->id_order }}"><i class="fa-solid fa-truck"></i> Lacak Pengiriman</a>
                            @endif
                          @endif
                          @if($order->opsi_pengiriman == 'otomatis')
                          <a href="{{ route('admin.order.resi', $order->no_order) }}" class="btn btn-primary" target="_blank">Cetak RESI</a>
                          @endif
                          <a href="{{ route('admin.order.invoice', $order->no_order) }}" class="btn btn-success" target="_blank">Cetak Invoice</a>
                        </div>
                      </div>
                        <div class="card-body">
                          <table class="table table-bordered">
                            <tbody>
                              <tr>
                                <td>Status Order</td>
                                <td>
                                  @if($order->status_order == 'pending')
                                    <span class="badge badge-secondary">Menunggu Pembayaran</span>
                                  @elseif($order->status_order == 'paid')
                                    <span class="badge badge-primary">Menunggu Konfirmasi</span>
                                  @elseif($order->status_order == 'process')
                                    <span class="badge badge-warning">Sedang Diproses</span>
                                  @elseif($order->status_order == 'delivered')
                                    <span class="badge badge-info">Sedang Dalam Pengiriman</span>
                                  @elseif($order->status_order == 'done' && $order->done == 0)
                                    <span class="badge badge-info">Pesanan telah sampai ditujuan</span>
                                  @elseif($order->status_order == 'done' && $order->done == 1)
                                    <span class="badge badge-success">Selesai</span>
                                  @elseif($order->status_order == 'cancel' && $order->dibatalkan_pembeli == 0)
                                    <span class="badge badge-danger">Dibatalkan System</span>
                                  @elseif($order->status_order == 'cancel' && $order->dibatalkan_pembeli == 2)
                                    <span class="badge badge-danger">Dibatalkan Admin dengan konfirmasi</span>
                                  @elseif($order->status_order == 'cancel' && $order->dibatalkan_pembeli == 1)
                                    <span class="badge badge-danger">Dibatalkan Pembeli</span>
                                  @endif
                                </td>
                              </tr>
                              <tr>
                                <td>Opsi Pengiriman</td>
                                <td>
                                  @if($order->opsi_pengiriman == 'otomatis')
                                    <span class="badge badge-primary">Otomatis (by system)</span>
                                  @elseif($order->opsi_pengiriman == 'manual')
                                    <span class="badge badge-danger">Ambil ditempat</span>
                                  @endif
                                </td>
                              </tr>
                              @if($order->status_order == 'cancel' && $order->dibatalkan_pembeli > 0)
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
                                <td>Total Harga Barang</td>
                                <td>{{ 'Rp. ' . number_format($order->total_harga_barang, 0, '.', '.') }}</td>
                              </tr>
                              <tr>
                                <td>Jumlah Total</td>
                                <td>{{ 'Rp. ' . number_format($order->jumlah_total, 0, '.', '.') }}</td>
                              </tr>
                              <tr>
                                <td>Total Tagihan</td>
                                <td>
                                  <div>{{ 'Rp. ' . number_format($order->total_tagihan, 0, '.', '.') }}</div>
                                  @if($order->coin_yang_digunakan > 0) <div class="text-success small">(Potongan Onelito Coins {{ 'Rp. ' . number_format($order->coin_yang_digunakan, 0, '.', '.') }})</div> @endif
                                </td>
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
                                <td>{{ \Carbon\Carbon::parse($order->paid_at)->format('Y M d H:i:s') }}</td>
                              </tr>
                              <tr>
                                <td>Destination Contact Name</td>
                                <td>{{ $order->destination_contact_name }}</td>
                              </tr>
                              <tr>
                                <td>Destination Contact Phone</td>
                                <td>
                                  <div class="d-flex align-items-center">
                                    <div class="text-success mr-1" id="phoneNumber">{{ $order->destination_contact_phone }}</div>
                                    <div>
                                      <button class="border" id="copyPhoneNumber"><i class="fa-solid fa-copy"></i></button>
                                      <button class="border" id="chatPhoneNumber"><i class="fa-solid fa-comments"></i></button>
                                    </div>
                                  </div>
                                </td>
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
                                <td>{{ $order->destination_coordinate_latitude }}</td>
                              </tr>
                              <tr>
                                <td>Destination Coordinate Longitude</td>
                                <td>{{ $order->destination_coordinate_longitude }}</td>
                              </tr>
                              @if($order->opsi_pengiriman == 'otomatis')
                              <tr>
                                <td>No Resi</td>
                                <td>{{ $order->waybill_id }}</td>
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
                                <td>{{ 'Rp. ' . number_format($order->courier_price, 0, '.', '.') }}</td>
                              </tr>
                              @endif
                              <tr>
                                <td>Barang</td>
                                <td class="p-0">
                                  <table class="table table-bordered m-0">
                                    <tbody>
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
                                      <tr>
                                        <td style="width: 20%;" class="p-0">
                                          <img src="{{ $detailOrderPhoto }}" alt="" class="img-fluid">
                                        </td>
                                        <td>
                                          <div>Nama : 
                                            <span style="font-weight: 800;">
                                              @if($detailOrder->productable_type === 'KoiStock')
                                                {{ $productable->variety }} | {{ $productable->breeder }} | {{ $productable->bloodline }} | {{ $productable->size }}
                                              @elseif($detailOrder->productable_type === 'Product')
                                                {{ $productable->merek_produk }} {{ $productable->nama_produk }}
                                              @endif
                                            </span>
                                          </div>
                                          <div>Jumlah : <span style="font-weight: 800;">{{ $detailOrder->jumlah_produk }}</span></div>
                                        </td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td>Order Note</td>
                                <td>{{ $order->order_note ?? '-' }}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="lacak-pengiriman{{ $order->id_order }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="">Lacak Pengiriman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
@endsection
@push('scripts')
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.all.js" integrity="sha512-AINSNy+d2WG9ts1uJvi8LZS42S8DT52ceWey5shLQ9ArCmIFVi84nXNrvWyJ6bJ+qIb1MnXR46+A4ic/AUcizQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const copyBtn = document.getElementById('copyPhoneNumber');
        const chatBtn = document.getElementById('chatPhoneNumber');
        const phoneNumberEl = document.getElementById('phoneNumber');

        copyBtn.addEventListener('click', function () {
            const phoneNumber = phoneNumberEl.innerText.trim();
            navigator.clipboard.writeText(phoneNumber).then(() => {
                alert('Nomor berhasil disalin: ' + phoneNumber);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        });

        chatBtn.addEventListener('click', function () {
            let phoneNumber = phoneNumberEl.innerText.trim();
            phoneNumber = phoneNumber.replace(/\D/g, '');
            if (phoneNumber.startsWith('0')) {
                phoneNumber = '62' + phoneNumber.substring(1);
            }
            const waLink = `https://wa.me/${phoneNumber}`;
            window.open(waLink, '_blank');
        });
    });
</script>
<script>
  $(document).ready(function() {
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
          console.log(data);
          if (data && data && data.success === true) {
            const trackingUpdates = data.history;
            let content = '';
            
            trackingUpdates.forEach(function(update) {
              const updatedAt = new Date(update.updated_at);
              const dateFormatted = updatedAt.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
              const timeFormatted = updatedAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

              let statusMessage = '';

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
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.select2').select2({});
  });

  $(document).ready(function(){
    $('.select3').select2({
      tags: true
    });
  });

  $('.delete2').click(function(event) {
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
</script>
@endpush