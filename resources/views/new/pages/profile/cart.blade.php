@extends('new.templates.pages')
@section('title', 'Cart')
@section('content')
<div class="py-2 py-md-5">
  <div class="row g-3">
    <div class="col-md-8">
      <div class="fw-bold fs-5 mb-3">Keranjang</div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="selectAll" {{ $carts->isEmpty() ? 'disabled' : 'checked' }}>
        <label class="form-check-label" for="selectAll">Pilih Semua</label>
      </div>
      <div class="py-3">
        @php
            $totalPrice = 0;
        @endphp
        @forelse($carts as $cart)
          @php
              $cartable = $cart->cartable;

              if ($cart->cartable_type === 'Product') {
                  $cartPrice = $cartable->harga;
                  $cartPhoto = $cartable->photo ? url('storage') . '/' . $cartable->photo->path_foto : '';
                  $isOutOfStock = $cartable->stock === 0;
              } elseif ($cart->cartable_type === 'KoiStock') {
                  $cartPrice = $cartable->harga_ikan;
                  $cartPhoto = url('storage') . '/' . $cartable->foto_ikan;
                  $isOutOfStock = $cartable->stock === 0;
              }

              $totalPrice += $isOutOfStock ? 0 : ($cartPrice * $cart->jumlah);
          @endphp
          <div class="d-flex mb-3 gap-2">
            <input class="form-check-input col-1 cart-checkbox" type="checkbox" value="{{ $cart->id_keranjang }}" id="cartCheck_{{ $cart->id_keranjang }}" {{ $isOutOfStock ? 'disabled' : 'checked' }}>
            <div class="row g-2">
              <div class="col-3 col-md-2"><img src="{{ $cartPhoto }}" alt="" class="img-fluid border p-2 rounded"></div>
              <div class="col-9 col-md-10">
                @if($isOutOfStock)
                <div class="text-danger fw-bold">Stock Habis</div>
                @endif
                <div class="@if($isOutOfStock) text-muted @endif">
                  @if($cart->cartable_type === 'KoiStock')
                    {{ $cartable->variety }} | {{ $cartable->breeder }} | {{ $cartable->bloodline }} | {{ $cartable->size }}
                  @elseif($cart->cartable_type === 'Product')
                    {{ $cartable->merek_produk }} {{ $cartable->nama_produk }}
                  @endif
                </div>
                <div class="mb-2 @if($isOutOfStock) text-muted @else fw-bold @endif">Rp. {{ number_format($cartPrice, 0, '.', '.') }}</div>
                <div class="@if($isOutOfStock) text-muted @else text-danger @endif small mb-2" @if(!$isOutOfStock) data-bs-toggle="modal" data-bs-target="#tulisCatatan{{ $cart->id_keranjang }}" @endif style="cursor: pointer;">Tulis Catatan</div>
                @if(!empty($cart->catatan))
                  <div class="mb-2 small text-muted border rounded p-2">{{ $cart->catatan }}</div>
                @endif
                <div class="d-flex col-md-3 border rounded">
                  <button class="btn hapusBarang" data-id-keranjang="{{ $cart->id_keranjang }}" data-price="{{ $cartPrice }}"> <i class="fa-solid fa-minus text-muted"></i></button>
                  <input readonly type="text" class="form-control border-0 bg-transparent text-center" value="{{ $cart->jumlah }}" id="jumlahBarang_{{ $cart->id_keranjang }}">
                  <button class="btn tambahBarang" data-id-keranjang="{{ $cart->id_keranjang }}" data-price="{{ $cartPrice }}"> <i class="fa-solid fa-plus text-muted"></i></button>
                </div>
              </div>
            </div>
          </div>
        @empty
        <div class="text-center">Kosong</div>
        @endforelse
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="fw-bold fs-5 mb-2">Ringkasan Belanja</div>
          <div class="d-flex justify-content-between text-muted">
            <div>Total</div>
            <div id="totalPrice">Rp. {{ number_format($totalPrice, 0, '.', '.') }}</div>
          </div>
        </div>
        <hr class="m-0">
        <div class="card-body">
          @php
            $auth = Auth::guard('member')->user();
          @endphp
          @if(!$auth->pilih_alamat)
          <div class="alert alert-danger">Pilih alamat terlebih dahulu <a class="text-dark" href="{{ route('alamat.index') }}">disini</a></div>
          @endif
          <button class="btn btn-danger w-100" id="buyButton" {{ $carts->isEmpty() ? 'disabled' : '' }}>Beli</button>
        </div>
      </div>
    </div>
  </div>
</div>
@foreach($carts as $cart)
<div class="modal fade" id="tulisCatatan{{ $cart->id_keranjang }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tulis Catatan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tulis-catatan', $cart->id_keranjang) }}" method="POST">
        @csrf
        <div class="modal-body">
          <textarea class="form-control" rows="3" name="catatan">{{ $cart->catatan }}</textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
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
    const $buyButton = $('#buyButton');
    const $cartCheckboxes = $('.cart-checkbox');
    const $selectAll = $('#selectAll');

    $buyButton.click(function() {
      const selectedIds = $('.cart-checkbox:checked').map(function() {
        return $(this).val();
      }).get();

      if (selectedIds.length > 0) {
        const shipmentUrl = `{{ route('shipment') }}?ids=${selectedIds.join(',')}`;
        window.location.href = shipmentUrl;
      } else {
        alert('Pilih minimal satu barang sebelum melanjutkan.');
      }
    });

    function updateTotalPrice() {
        let totalPrice = 0;

        $('.cart-checkbox:checked').each(function () {
            const idKeranjang = $(this).val();
            const quantity = parseInt($(`#jumlahBarang_${idKeranjang}`).val());
            const price = $(this).closest('.d-flex').find('.tambahBarang, .hapusBarang').data('price');
            totalPrice += price * quantity;
        });

        $('#totalPrice').text(`Rp. ${totalPrice.toLocaleString('id-ID')}`);
        toggleBuyButton();
    }

    function toggleBuyButton() {
        const selectedCount = $('.cart-checkbox:checked').length;
        $('#buyButton').prop('disabled', selectedCount === 0);
    }

    $selectAll.click(function () {
        if (!$(this).prop('disabled')) {
            const isChecked = this.checked;

            $cartCheckboxes.each(function () {
                if (!$(this).is(':disabled')) {
                    $(this).prop('checked', isChecked);
                }
            });

            updateTotalPrice();
        }
    });

    $cartCheckboxes.change(function () {
        const allChecked = $cartCheckboxes.filter(':not(:disabled)').length === $cartCheckboxes.filter(':checked').length;
        $selectAll.prop('checked', allChecked);
        updateTotalPrice();
    });

    $('.tambahBarang').click(function() {
      const idKeranjang = $(this).data('id-keranjang');
      const $jumlahInput = $(`#jumlahBarang_${idKeranjang}`);
      $.ajax({
        url: `{{ url('tambah-barang') }}/${idKeranjang}`,
        type: 'GET',
        success: function(response) {
          if (response.success) {
            $jumlahInput.val(response.jumlah);
            updateTotalPrice();
          }
        }
      });
    });

    $('.hapusBarang').click(function() {
      const idKeranjang = $(this).data('id-keranjang');
      const $jumlahInput = $(`#jumlahBarang_${idKeranjang}`);
      $.ajax({
        url: `{{ url('hapus-barang') }}/${idKeranjang}`,
        type: 'GET',
        success: function(response) {
          if (response.success) {
            $jumlahInput.val(response.jumlah);
            updateTotalPrice();

            if (response.status_aktif === 0) {
              $(`#cartCheck_${idKeranjang}`).closest('.d-flex').remove();
              updateTotalPrice();
            }
          }
        }
      });
    });

    updateTotalPrice();
  });
</script>

@endpush
