@extends('new.templates.pages')
@section('title', 'Shipment')
@section('content')
@php
  $auth = Auth::guard('member')->user();
@endphp
<form action="{{ route('order') }}" method="POST">
  @csrf
  <div class="py-2 py-md-5">
    <div class="fw-bold mb-2"><a href="{{ url()->previous() }}" class="text-dark text-decoration-none"><i class="fas fa-arrow-left fs-4"></i></a></div>
    @if(Session::get('success'))
      <div class="alert alert-important alert-success mb-2" role="alert">
        {{ Session::get('success') }}
      </div>
    @endif
    @if(Session::get('error'))
      <div class="alert alert-important alert-danger mb-2" role="alert">
        {{ Session::get('error') }}
      </div>
    @endif
    <div class="row g-3">
      <div class="col-md-8">
        <div class="fw-bold fs-5 mb-2">Checkout</div>
        <div class="text-danger mb-4">Ini halaman terakhir dari proses belanjamu. Pastikan semua sudah benar.</div>
        <div class="mb-2">Barang yang dibeli :</div>
        <div class="py-3">
          @php
              $totalPrice = 0;
          @endphp
          @forelse($carts as $cart)
            @php
                $cartable = $cart->cartable;

                if ($cart->cartable_type === 'Product') {
                    $cartPrice = $cartable->harga;
                    if ($cart->cartable->photo !== null) {
                        $cartPhoto = url('storage') . '/' . $cart->cartable->photo->path_foto;
                    }
                } elseif ($cart->cartable_type === 'KoiStock') {
                    $cartPrice = $cartable->harga_ikan;
                    $cartPhoto = url('storage') . '/' . $cartable->foto_ikan;
                }
                
                $totalPrice += $cartPrice * $cart->jumlah;
            @endphp
            <div class="d-flex mb-3 gap-2">
              <div class="row g-2">
                <div class="col-3 col-md-2"><img src="{{ $cartPhoto }}" alt="" class="img-fluid border p-2 rounded"></div>
                <div class="col-9 col-md-10">
                  <div>
                    @if($cart->cartable_type === 'KoiStock')
                      {{ $cartable->variety }} | {{ $cartable->breeder }} | {{ $cartable->bloodline }} | {{ $cartable->size }}
                    @elseif($cart->cartable_type === 'Product')
                      {{ $cartable->merek_produk }} {{ $cartable->nama_produk }}
                    @endif
                  </div>
                  <div class="mb-2 fw-bold">Rp. {{ number_format($cartPrice, 0, '.', '.') }} x {{ $cart->jumlah }}</div>
                  @if(!empty($cart->catatan))
                      <div class="mb-2 small text-muted border rounded p-2">{{ $cart->catatan }}</div>
                  @endif
                </div>
              </div>
            </div>
          @empty
          <div class="text-center">Kosong</div>
          @endforelse
        </div>
        <div class="fw-bold fs-5 mb-2">Opsi Pengiriman</div>
        <div class="mb-3">
          <select class="form-select" id="opsiPengiriman" name="opsi_pengiriman" required>
            <option value="" disabled selected>Pilih opsi pengiriman</option>
            <option value="otomatis">Otomatis (by system)</option>
            <option value="manual">Manual (dihubungi admin)</option>
          </select>
        </div>
        <div class="alert alert-danger">Untuk pembelian ikan, ongkos kirim yang tertera adalah untuk pengiriman invoice. Sedangkan ongkos kirim untuk pengiriman ikan akan diinformasikan kembali oleh admin.</div>
        <div id="pengirimanOtomatis">
          <div class="card">
            <div class="card-body">
              @foreach($alamats as $alamat)
                @if($auth->pilih_alamat == $alamat->id)
                  <div class="d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#pilihAlamat">
                    <div class="col-11">
                      <div class="d-flex gap-2 align-items-center text-truncate">
                        @if($auth->alamat_utama == $alamat->id)
                          <div class="badge bg-secondary">Utama</div>
                        @endif
                        <div class="fw-bold">{{ $alamat->label }}</div>
                        <div>{{ $alamat->nama }}</div>
                        <div>{{ $alamat->no_hp }}</div>
                      </div>
                      <div class="text-truncate">{{ $alamat->alamat_lengkap }}</div>
                    </div>
                    <div class="col-1 text-center"><i class="fa-solid fa-angle-right"></i></div>
                  </div>
                @endif
              @endforeach
            </div>
            <hr class="m-0">
            <div class="card-body">
              <div class="row g-2">
                <div class="col">
                  <label class="form-label fw-bold">Pengiriman</label>
                  <select class="select2" name="pengiriman" id="pengiriman" required style="width: 100%;">
                      <option value="" selected disabled>Pilih Pengiriman</option>
                      <option value="instant">Instant</option>
                      <option value="same_day">Same Day</option>
                      <option value="next_day">Next Day</option>
                      <option value="reguler">Reguler</option>
                  </select>
                </div>
                <div class="col">
                  <label class="form-label fw-bold">Kurir</label>
                  <select class="kurir" name="kurir" id="kurir" required style="width: 100%;" disabled>
                    <option value="" selected disabled>Pilih Kurir</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-2">
          <div class="card-body">
            <div class="fw-bold mb-2"><i class="fa-solid fa-coins"></i> Coins</div>
            <div class="d-flex justify-content-between text-muted">
              <div class="">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="coin" disabled>
                  <label class="form-check-label">
                    Gunakan Coin
                  </label>
                </div>
              </div>
              <div>Rp. <span>{{ number_format($auth->coin, 0, '.', '.') }}</span></div>
            </div>
            <div class="text-muted small">*Untuk menggunakan coin pilih pengiriman dan kurir terlebih dahulu</div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="fw-bold mb-2">Ringkasan Belanja</div>
            <div class="d-flex justify-content-between text-muted">
              <div class="">Total Harga (<span id="totalJumlahBarang">{{ $totalJumlahBarang }}</span> Barang)</div>
              <div id="totalHarga">Rp. {{ number_format($totalPrice, 0, '.', '.') }}</div>
            </div>
            <div class="d-flex justify-content-between text-muted">
              <div class="">Total Ongkos Kirim</div>
              <div>Rp. <span id="totalOngkosKirim">0</span></div>
            </div>
          </div>
          <hr class="m-0">
          <div class="card-body">
            <div class="d-flex justify-content-between text-muted">
              <div class="">Coin Yang Digunakan</div>
              <div>Rp. <span id="coinYangDigunakan">0</span></div>
            </div>
            <div class="d-flex justify-content-between text-muted">
              <div class="">Sisa Coin</div>
              <div>Rp. <span id="sisaCoin">0</span></div>
            </div>
            <div class="d-flex justify-content-between fw-bold">
              <div class="">Jumlah Total</div>
              <div>Rp. <span id="jumlahTotal">{{ number_format($totalPrice, 0, '.', '.') }}</span></div>
            </div>
            <div class="d-flex justify-content-between fw-bold">
              <div class="">Total Tagihan</div>
              <div>Rp. <span id="totalTagihan">{{ number_format($totalPrice, 0, '.', '.') }}</span></div>
            </div>
          </div>
          <hr class="m-0">
          <div class="card-body">
              <input type="hidden" class="form-control" name="coin_yang_digunakan" value="0">
              <input type="hidden" class="form-control" name="total_harga_barang" value="{{ $totalPrice }}">
              <input type="hidden" class="form-control" name="peserta_id" value="{{ $auth->id_peserta }}">
              <input type="hidden" class="form-control" name="ids" value="{{ request('ids') }}">
              <input type="hidden" class="form-control" name="jumlah_total" value="">
              <input type="hidden" class="form-control" name="total_tagihan" value="">
              <input type="hidden" class="form-control" name="courier_name" value="">
              <input type="hidden" class="form-control" name="courier_code" value="">
              <input type="hidden" class="form-control" name="courier_type" value="">
              <input type="hidden" class="form-control" name="courier_service_name" value="">
              <input type="hidden" class="form-control" name="ongkos_kirim" value="">
              <button id="buttonBayar" type="submit" class="btn btn-danger w-100" disabled>Order</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="pilihAlamat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Alamat</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-2">
          @foreach($alamats as $alamat)
            <div class="col-12">
              <div class="card @if($auth->pilih_alamat == $alamat->id) border-danger alert-danger @endif">
                <div class="card-body p-4">
                  <div class="row g-3 align-items-center">
                    <div class="col-md-10">
                      <div class="fw-bold small mb-1">{{ $alamat->label }} @if($auth->alamat_utama == $alamat->id) <span class="badge bg-secondary">Utama</span> @endif</div>
                      <div class="fw-bold mb-1">{{ $alamat->nama }}</div>
                      <div class="mb-1">{{ $alamat->no_hp }}</div>
                      <div class="mb-1">{{ $alamat->alamat_lengkap }}</div>
                      <div class="d-md-flex align-items-center d-block gap-2">
                        @if($auth->alamat_utama != $alamat->id)
                          <a href="{{ route('alamat.alamat-utama', ['alamatId' => $alamat->id]) }}" class="text-danger small text-decoration-none">Jadikan Alamat Utama</a>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-2 @if($auth->pilih_alamat == $alamat->id) d-none d-md-flex align-items-center @endif">
                      @if($auth->pilih_alamat == $alamat->id)
                        <div class="m-auto"><i class="fa-solid fa-check text-danger"></i></div>
                      @else
                        <a href="{{ route('alamat.pilih-alamat', ['alamatId' => $alamat->id]) }}" class="btn btn-danger d-none d-md-block">Pilih</a>
                        <a href="{{ route('alamat.pilih-alamat', ['alamatId' => $alamat->id]) }}" class="btn btn-danger w-100 d-block d-md-none">Pilih</a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    const userCoin = parseInt('{{ $auth->coin }}', 10);
    const coinCheckbox = $('#coin');
    const kurirSelect = $('#kurir');
    const totalTagihanElement = $('#totalTagihan');
    const jumlahTotalElement = $('#jumlahTotal');
    const totalHargaElement = $('#totalHarga');
    const coinYangDigunakanElement = $('#coinYangDigunakan');
    const sisaCoinElement = $('#sisaCoin');
    let totalHarga = parseInt(totalHargaElement.text().replace(/[^\d]/g, ''), 10);

    function updateButtonState() {
        const opsiPengiriman = $('#opsiPengiriman').val();
        const kurir = $('#kurir').val();

        // 1. Jika opsi pengiriman belum dipilih sama sekali, disable tombol
        if (!opsiPengiriman) {
            $('#buttonBayar').prop('disabled', true);
            return;
        }

        // 2. Jika opsi adalah "otomatis", tombol hanya aktif jika kurir sudah dipilih
        if (opsiPengiriman === 'otomatis') {
            if (kurir === null || kurir === '') {
                $('#buttonBayar').prop('disabled', true);
            } else {
                $('#buttonBayar').prop('disabled', false);
            }
        } 
        // 3. Jika opsi adalah "manual", tombol langsung aktif
        else if (opsiPengiriman === 'manual') {
            $('#buttonBayar').prop('disabled', false);
        }
    }

    // Panggil fungsi ini saat halaman dimuat untuk men-disable tombol di awal
    updateButtonState();

    // --- END: KODE BARU ---


    // Sembunyikan div pengiriman otomatis saat halaman dimuat
    $('#pengirimanOtomatis').hide();
    
    // Nonaktifkan input di dalamnya agar tidak 'required' saat tersembunyi
    $('#pengirimanOtomatis').find('select').prop('disabled', true);

    // Tambahkan event listener ke dropdown opsi pengiriman
    $('#opsiPengiriman').on('change', function() {
        var selectedOption = $(this).val();

        if (selectedOption === 'otomatis') {
            // Tampilkan div dengan efek slide down
            $('#pengirimanOtomatis').slideDown();
            // Aktifkan kembali select di dalamnya dan buat mereka 'required'
            $('#pengiriman, #kurir').prop('disabled', false).prop('required', true);
        } else if (selectedOption === 'manual') {
            // Sembunyikan div dengan efek slide up
            $('#pengirimanOtomatis').slideUp();
            // Nonaktifkan select di dalamnya dan hapus atribut 'required'
            $('#pengiriman, #kurir').prop('disabled', true).prop('required', false);

            // Reset biaya pengiriman dan total jika sebelumnya sudah dipilih
            $('#totalOngkosKirim').text('0');
            $('input[name="ongkos_kirim"]').val('');
            var totalHargaBarang = parseInt($('input[name="total_harga_barang"]').val(), 10);
            $('#jumlahTotal').text(new Intl.NumberFormat('id-ID').format(totalHargaBarang));
            $('#totalTagihan').text(new Intl.NumberFormat('id-ID').format(totalHargaBarang));
            $('input[name="jumlah_total"]').val(totalHargaBarang);
            $('input[name="total_tagihan"]').val(totalHargaBarang);

            // Reset juga penggunaan coin
            $('#coin').prop('checked', false).trigger('change');
            $('#coin').prop('disabled', true);
        }

        // Panggil fungsi untuk update status tombol setiap kali opsi pengiriman berubah
        updateButtonState();
    });

    kurirSelect.on('change', function() {
        if (kurirSelect.val() !== null && kurirSelect.val() !== '') {
            coinCheckbox.prop('disabled', false);
        } else {
            coinCheckbox.prop('disabled', true);
        }
    });

    if (kurirSelect.val() === null || kurirSelect.val() === '') {
        coinCheckbox.prop('disabled', true);
    }

    if (userCoin === 0) {
        coinCheckbox.prop('disabled', true);
    }
    
    coinCheckbox.on('change', function () {
        const ongkosKirim = parseInt($('input[name="ongkos_kirim"]').val() || 0, 10);
        let currentTotal = totalHarga + ongkosKirim;
        let coinYangDigunakan = 0;
        let sisaCoin = userCoin;

        if ($(this).is(':checked') && userCoin > 0) {
            // Determine coin usage based on current total
            coinYangDigunakan = Math.min(userCoin, currentTotal);
            currentTotal -= coinYangDigunakan;
            sisaCoin -= coinYangDigunakan;
        }

        // Ensure currentTotal is not negative
        currentTotal = Math.max(0, currentTotal);

        // Update the elements on the page
        totalTagihanElement.text(new Intl.NumberFormat('id-ID').format(currentTotal));
        coinYangDigunakanElement.text(new Intl.NumberFormat('id-ID').format(coinYangDigunakan));
        sisaCoinElement.text(new Intl.NumberFormat('id-ID').format(sisaCoin));

        // Update hidden form values
        $('input[name="total_tagihan"]').val(currentTotal);
        $('input[name="coin_yang_digunakan"]').val(coinYangDigunakan);
    });

    // Initial Values for Form and Display
    $('input[name="total_tagihan"]').val(totalHarga);
    $('input[name="jumlah_total"]').val(totalHarga);
    coinYangDigunakanElement.text('0');
    sisaCoinElement.text(new Intl.NumberFormat('id-ID').format(userCoin));

    $('input[name="total_tagihan"]').val('');
    $('input[name="jumlah_total"]').val('');
    $('input[name="courier_type"]').val('');
    $('input[name="courier_name"]').val('');
    $('input[name="courier_code"]').val('');
    $('input[name="courier_service_name"]').val('');
    $('input[name="ongkos_kirim"]').val('');

    $('.select2').select2({});

    $('.kurir').select2({
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.html;
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    function checkKurirSelection() {
        if ($('#kurir').val() === null || $('#kurir').val() === '') {
            $('#buttonBayar').prop('disabled', true);
        } else {
            $('#buttonBayar').prop('disabled', false);
        }
    }

    $('#kurir').on('change', function() {
        updateButtonState();
    });

    updateButtonState();
  });

  var couriers = @json($couriers);
  var courierCodes = Array.from(new Set(couriers.map(function(courier) {
    return courier.courier_code;
  }))).join(',');

  console.log(courierCodes);

  $(document).ready(function() {
      $('#courier-select').on('change', function() {
        var selectedCourierCode = $(this).val();
        
        $('#shipping-select option').show();
        
        $('#shipping-select option').each(function() {
            if ($(this).data('courier-code') !== selectedCourierCode) {
                $(this).hide();
            }
        });

        $('#shipping-select').val('').prop('selected', true);
      });

      $('#pengiriman').change(function() {
          $('input[name="total_tagihan"]').val('');
          $('input[name="jumlah_total"]').val('');
          $('input[name="courier_name"]').val('');
          $('input[name="courier_code"]').val('');
          $('input[name="courier_type"]').val('');
          $('input[name="courier_service_name"]').val('');
          $('input[name="ongkos_kirim"]').val('');

          $('#kurir').empty();
          $('#kurir').append('<option value="" selected disabled>Pilih</option>');
          $('#kurir').val(null).trigger('change');
          $('#totalOngkosKirim').text('0');
          var currentTotalPrice = parseInt($('#totalHarga').text().replace(/[^\d]/g, ''));
          $('#totalTagihan').text(new Intl.NumberFormat('id-ID').format(currentTotalPrice));
          $('#jumlahTotal').text(new Intl.NumberFormat('id-ID').format(currentTotalPrice));

          var pengiriman = $(this).val();

          var postalCode = '{{ $shipper->kode_pos }}';
          var originLatitude = '{{ $shipper->latitude }}';
          var originLongitude = '{{ $shipper->longitude }}';
          var originAreaId = '{{ $shipper->id_lokasi }}';
          var destinationLatitude = '{{ $destination->latitude }}';
          var destinationLongitude = '{{ $destination->longitude }}';
          var destinationAreaId = '{{ $destination->id_lokasi }}';

          var carts = @json($carts);
          
          var items = [];

          carts.forEach(function(cart) {
              if (cart.cartable_type === 'Product') {
                  items.push({
                      'name': cart.cartable.merek_produk + ', ' + cart.cartable.nama_produk,
                      'description': '',
                      'category': '',
                      'value': cart.cartable.harga,
                      'quantity': cart.jumlah,
                      'height': cart.cartable.height,
                      'length': cart.cartable.length,
                      'weight': cart.cartable.weight,
                      'width': cart.cartable.width,
                  });
              } else if (cart.cartable_type === 'KoiStock') {
                  items.push({
                      'name': cart.cartable.variety + ', ' + cart.cartable.breeder + ', ' + cart.cartable.bloodline + ', ' + cart.cartable.size,
                      'description': '',
                      'category': '',
                      'value': cart.cartable.harga,
                      'quantity': 1,
                      'height': 3,
                      'length': 10,
                      'weight': 10,
                      'width': 10,
                  });
              }
          });

          var requestData = {
              origin_area_id: originAreaId,
              origin_postal_code: postalCode,
              origin_latitude: originLatitude,
              origin_longitude: originLongitude,
              destination_area_id: destinationAreaId,
              destination_latitude: destinationLatitude,
              destination_longitude: destinationLongitude,
              couriers: courierCodes,
              items: items
          };

          console.log(requestData);

          $.ajax({
              url: 'https://api.biteship.com/v1/rates/couriers',
              type: 'POST',
              dataType: 'json',
              headers: {
                  'Authorization': 'Bearer {{ env('BITESHIP_API_KEY') }}',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              },
              data: JSON.stringify(requestData),
              beforeSend: function() {
                $('#kurir').prop('disabled', true);
              },
              success: function(response) {
                  console.log(response);
                  $('#kurir').prop('disabled', false);
                  var filteredPricing = response.pricing.filter(function(price) {
                      switch (pengiriman) {
                          case 'reguler':
                              return price.service_type === 'standard';
                          case 'next_day':
                              return price.service_type === 'overnight';
                          case 'same_day':
                              return price.service_type === 'same_day' && price.type === 'same_day';
                          case 'instant':
                              return price.service_type === 'same_day' && price.type === 'instant';
                          default:
                              return false;
                      }
                  });

                  if (filteredPricing.length === 0) {
                      var noCouriersMessage = [{
                          id: 'no-courier',
                          text: 'Kurir untuk pengiriman ke alamat tujuan tidak ada, lengkapi alamat anda',
                          html: '<div>Kurir untuk pengiriman ke alamat tujuan tidak ada, dimohon untuk melengkapi alamat tujuan.</div>',
                          disabled: true
                      }];

                      $('#kurir').select2({
                          data: noCouriersMessage,
                          escapeMarkup: function(markup) {
                              return markup;
                          },
                          templateResult: function(data) {
                              return data.html;
                          },
                          templateSelection: function(data) {
                              return data.text;
                          }
                      });
                  } else {
                      var data = filteredPricing.map(function(price, index) {
                          var formattedPrice = new Intl.NumberFormat('id-ID', {
                              style: 'currency',
                              currency: 'IDR'
                          }).format(price.price);

                          return {
                              id: price.courier_code,
                              text: `${price.courier_name} ${price.courier_service_name}`,
                              html: `<div style="font-weight:bold;">${price.courier_name} ${price.courier_service_name}</div>
                                  <div><small>${price.duration}</small></div>
                                  <div><small>${price.description}</small></div>
                                  <div>${formattedPrice}</div>`,
                              title: price.courier_name,
                              'data-price': price.price,
                              'data-courier-type': price.type,
                              'data-courier-name': price.courier_name,
                              'data-courier-code': price.courier_code,
                              'data-courier-service-name': price.courier_service_name,
                          };
                      });

                      $('#kurir').select2({
                          data: data,
                          escapeMarkup: function(markup) {
                              return markup;
                          },
                          templateResult: function(data) {
                              return data.html;
                          },
                          templateSelection: function(data) {
                              return data.text;
                          }
                      });
                  }

                  checkKurirSelection();
              },
              error: function(xhr, status, error) {
                  console.error(error);
                  $('#kurir').prop('disabled', false);
              }
          });
      });

      $('#kurir').on('select2:select', function(e) {
        var selectedData = e.params.data;
        var selectedCourierPrice = selectedData['data-price'];
        var selectedCourierType = selectedData['data-courier-type'];
        var selectedCourierName = selectedData['data-courier-name'];
        var selectedCourierCode = selectedData['data-courier-code'];
        var selectedCourierServiceName = selectedData['data-courier-service-name'];
        var selectedCourierDuration = selectedData['data-courier-duration'];

        if (selectedCourierPrice) {
            var formattedPrice = new Intl.NumberFormat('id-ID').format(selectedCourierPrice);
            $('#totalOngkosKirim').text(formattedPrice);
            $('input[name="ongkos_kirim"]').val(selectedCourierPrice);

            var currentTotalPrice = parseInt($('#totalHarga').text().replace(/[^\d]/g, ''));

            var updatedTotalPrice = currentTotalPrice + selectedCourierPrice;

            var formattedTotalPrice = new Intl.NumberFormat('id-ID').format(updatedTotalPrice);
            $('#totalTagihan').text(formattedTotalPrice);
            $('#jumlahTotal').text(formattedTotalPrice);

            $('input[name="total_tagihan"]').val(updatedTotalPrice);
            $('input[name="jumlah_total"]').val(updatedTotalPrice);
            $('input[name="courier_type"]').val(selectedCourierType);
            $('input[name="courier_name"]').val(selectedCourierName);
            $('input[name="courier_code"]').val(selectedCourierCode);
            $('input[name="courier_service_name"]').val(selectedCourierServiceName);
        } else {
            $('#totalOngkosKirim').text('0');
            var currentTotalPrice = parseInt($('#totalHarga').text().replace(/[^\d]/g, ''));
            $('#totalTagihan').text(new Intl.NumberFormat('id-ID').format(currentTotalPrice));
            $('#jumlahTotal').text(new Intl.NumberFormat('id-ID').format(currentTotalPrice));
            $('input[name="total_tagihan"]').val(currentTotalPrice);
            $('input[name="jumlah_total"]').val(currentTotalPrice);
        }
    });
  });
</script>
@endpush