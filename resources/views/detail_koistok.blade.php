@extends('layout.main')
@section('container')
@php
  $photo = url('/img/koi12.jpg');
  if ($fish->foto_ikan !== null) {
    $photo = url('storage') . '/' . $fish->foto_ikan;
  }

  $auth = Auth::guard('member')->user();
@endphp
<br><br><br>
<div class="container py-3 py-md-5">
    <div class="fw-bold mb-3"><a href="/koi_stok" class="text-dark text-decoration-none"><i class="fas fa-arrow-left fs-4"></i></a></div>
    <div class="row g-2 g-md-3">
        <div class="col-md-3">
            <div class="mb-2"><img src="{{ $photo }}" alt="" class="img-fluid"></div>
            <div><a href="{{ $fish->link_video }}" target="_blank" class="btn btn-danger w-100">Video</a></div>
        </div>
        <div class="col-md-6">
          <div class="fs-5 fw-bold mb-2">{{ $fish->variety }} | {{ $fish->breeder }} | {{ $fish->size }} | {{ $fish->sex }}</div>
          <div class="fs-5 fw-bold mb-2">Rp. {{ number_format($fish->harga_ikan, 0, '.', '.') }}</div>
          <div class="text-success fw-bold mb-2">Detail</div>
          <div class="">Breeder : {{ $fish->breeder }}</div>
          <div class="">Bloodline : {{ $fish->bloodline }}</div>
          <div class="">Sex : {{ $fish->sex }}</div>
          <div class="">DOB : {{ $fish->dob }}</div>
          <div class="mb-3">Size : {{ $fish->size }}</div>
          <div class="fw-bold">Note :</div>
          <div>{!! $fish->note !!}</div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <div class="mb-2 fw-bold">Atur jumlah</div>
              <div class="mb-2 small">Stok Total : <span class="text-success fw-bold">Sisa <span id="sisaStok">{{ $fish->stock }}</span></span></div>
              <div class="d-flex mb-2 border rounded">
                <button id="hapusBarang" class="btn"><i class="fa-solid fa-minus text-muted"></i></button>
                <input id="jumlahBarang" readonly type="text" class="form-control border-0 bg-transparent text-center" value="1">
                <button id="tambahBarang" class="btn"><i class="fa-solid fa-plus text-muted"></i></button>
              </div>
              <div class="d-flex justify-content-between small mb-3">
                <div class="text-muted">Subtotal</div>
                <div class="fw-bold" id="hargaTotal">Rp. {{ number_format($fish->harga_ikan, 0, '.', '.') }}</div>
              </div>
              <div class="mb-3">
                <button id="keranjangButton" class="mb-2 btn btn-success w-100"><i class="fa-solid fa-plus"></i> Tambah Keranjang</button>
                {{-- <button id="beliLangsungButton" class="btn btn-outline-success w-100">Beli Langsung</button> --}}
              </div>
              <div class="d-flex justify-content-between small">
                <div style="cursor: pointer;"><a href="https://wa.me/0811972857" target="_blank" class="text-decoration-none text-dark"><i class="fa-regular fa-comment"></i> Chat</a></div>
                @if($auth)
                <div style="cursor: pointer;" id="wishlist"><i class="{{ $isWishlisted ? 'fa-solid text-danger' : 'fa-regular' }} fa-heart"></i> Wishlist</div>
                @else
                <a href="{{ route('login') }}" style="cursor: pointer;" class="text-dark text-decoration-none"><i class="fa-regular fa-heart"></i> Wishlist</a>
                @endif
                <div style="cursor: pointer;" id="shareProduk"><i class="fa-solid fa-share-nodes"></i> Share</div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  document.addEventListener('DOMContentLoaded', () => {
    const jumlah = parseInt(jumlahBarang.value);
    const stok = parseInt(document.getElementById('sisaStok').textContent);

    if (jumlah >= stok) {
        tambahBarang.disabled = true;
    }

    if (jumlah <= 1) {
        hapusBarang.disabled = true;
    }
  });

  const tambahBarang = document.getElementById('tambahBarang');
  const hapusBarang = document.getElementById('hapusBarang');
  const jumlahBarang = document.getElementById('jumlahBarang');
  const hargaTotal = document.getElementById('hargaTotal');
  const shareProduk = document.getElementById('shareProduk');
  const wishlist = document.getElementById('wishlist');
  const keranjangButton = document.getElementById('keranjangButton');
  const beliLangsungButton = document.getElementById('beliLangsungButton');

  const hargaSatuan = {{ $fish->harga_ikan }};
  const idProduk = {{ $fish->id_koi_stock }};
  var isWishlisted = @json($isWishlisted) ? true : false;

  function updateHargaTotal() {
      const jumlah = parseInt(jumlahBarang.value);
      const total = hargaSatuan * jumlah;
      hargaTotal.textContent = `Rp. ${total.toLocaleString('id-ID')}`;
  }

  function checkStok() {
    const stok = parseInt(document.getElementById('sisaStok').textContent);
    if (stok <= 0) {
        tambahBarang.disabled = true;
        keranjangButton.disabled = true;
        beliLangsungButton.disabled = true;
    } else {
        tambahBarang.disabled = false;
        keranjangButton.disabled = false;
        beliLangsungButton.disabled = false;
    }
  }

  tambahBarang.addEventListener('click', () => {
      let jumlah = parseInt(jumlahBarang.value);
      const stok = parseInt(document.getElementById('sisaStok').textContent);
      if (jumlah < stok) {
          jumlah += 1;
          jumlahBarang.value = jumlah;
          updateHargaTotal();
      }
      
      if (jumlah >= stok) {
        tambahBarang.disabled = true;
      }

      if (jumlah > 1) {
        hapusBarang.disabled = false;
      }
  });

  hapusBarang.addEventListener('click', () => {
    let jumlah = parseInt(jumlahBarang.value);
    const stok = parseInt(document.getElementById('sisaStok').textContent);
    if (jumlah > 1) {
        jumlah -= 1;
        jumlahBarang.value = jumlah;
        updateHargaTotal();
    }

    if (jumlah < stok) {
        tambahBarang.disabled = false;
    }

    if (jumlah <= 1) {
        hapusBarang.disabled = true;
    }
  });

  shareProduk.addEventListener('click', () => {
      const currentUrl = window.location.href;
      navigator.clipboard.writeText(currentUrl).then(() => {
          alert('Link produk berhasil disalin');
      }).catch(err => {
          console.error('Gagal menyalin link: ', err);
      });
  });

  wishlist.addEventListener('click', function() {
      const method = isWishlisted ? 'DELETE' : 'POST';
      const url = isWishlisted ? `/wishlists/${idProduk}` : '/wishlists';

      $.ajax({
          type: method,
          url: url,
          data: {
              id_produk: idProduk
          },
          dataType: 'json',
          success: function(response) {
              isWishlisted = !isWishlisted;
              const message = isWishlisted ? 'Produk berhasil ditambahkan ke wishlist' : 'Produk berhasil dihapus dari wishlist';
              alert(message);
              const icon = $(wishlist).find('i');
              if (isWishlisted) {
                  icon.removeClass('fa-regular').addClass('fa-solid text-danger');
              } else {
                  icon.removeClass('fa-solid text-danger').addClass('fa-regular');
              }
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
              alert('Terjadi kesalahan, silakan coba lagi.');
          }
      });
  });

  keranjangButton.addEventListener('click', function() {
      const jumlah = parseInt(jumlahBarang.value);
      $.ajax({
          type: 'POST',
          url: `/carts`,
          data: {
              jumlah: jumlah,
              cartable_id: idProduk,
              cartable_type: 'KoiStock',
          },
          dataType: 'json',
          success: function(response) {
              alert('Produk berhasil ditambahkan ke keranjang');
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
          }
      });
  });

  checkStok();
  updateHargaTotal();
</script>
@endpush