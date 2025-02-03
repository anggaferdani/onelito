<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title></title>
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top" id="navbar">
    <div class="container-fluid">
      <a class="btn btn-success fw-bold ms-auto" href="javascript:void(0)" onclick="printWithoutNavbar()">Cetak</a>
    </div>
  </nav>
  <div class="col-md-6 mx-auto my-3">
    <div class="container">
      <div class="row align-items-center mb-5">
        <div class="col-6">
          <div><img src="{{ asset('img/logo-onelito.jpg') }}" alt="" class="img-fluid" width="200"></div>
        </div>
        <div class="col-6">
          <div class="fw-bold text-end small">INVOICE</div>
          <div class="text-success text-end small fw-bold">{{ $order->no_order }}</div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="fw-bold small">STATUS :
            @if($order->status_order == 'pending')
              <div class="text-success">MENUNGGU PEMBAYARAN</div>
            @else
              <div class="text-success">LUNAS</div>
            @endif
          </div>
        </div>
      </div>
      <div class="row g-3 g-md-0 mb-5">
        <div class="col-md-5">
          <div class="fw-bold small">DITERBITKAN ATAS NAMA</div>
          <div>
            <table style="width: 100%;">
              <tbody>
                <tr>
                  <td class="small text-nowrap" style="width: 20%; vertical-align: top;">Penjual</td>
                  <td style="width: 3%; vertical-align: top;">:</td>
                  <td class="small fw-bold">{{ $order->shipper_contact_name }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-7">
          <div class="fw-bold small">UNTUK</div>
          <div>
            <table style="width: 100%;">
              <tbody>
                <tr>
                  <td class="small text-nowrap" style="width: 40%; vertical-align: top;">Pembeli</td>
                  <td style="width: 3%; vertical-align: top;">:</td>
                  <td class="small fw-bold">{{ $order->destination_contact_name }}</td>
                </tr>
                <tr>
                  <td class="small text-nowrap" style="width: 40%; vertical-align: top;">Tanggal Pembelian</td>
                  <td style="width: 3%; vertical-align: top;">:</td>
                  <td class="small fw-bold">{{ \Carbon\Carbon::parse($order->tanggal)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                  <td class="small text-nowrap" style="width: 40%; vertical-align: top;">Alamat Pengiriman</td>
                  <td style="width: 3%; vertical-align: top;">:</td>
                  <td class="small"><span class="fw-bold">{{ $order->destination_contact_name }}</span> ({{ $order->destination_contact_phone }}) {{ $order->destination_address }} {{ $order->destination_postal_code }} @if($order->destination_note) ({{ $order->destination_note }}) @endif</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="w-100">
        <table class="w-100 table mb-3">
          <thead>
            <tr>
              <td class="fw-bold small">INFO PRODUK</td>
              <td class="text-end fw-bold text-nowarp small">JUMLAH</td>
              <td class="text-end fw-bold text-nowarp small">HARGA SATUAN</td>
              <td class="text-end fw-bold text-nowarp small">TOTAL HARGA</td>
            </tr>
          </thead>
          <tbody>
            @foreach($order->details as $detailOrder)
              <tr>
                <td class="">
                  <div class="text-success fw-bold small">{{ $detailOrder->name }}</div>
                  <div class="small">Berat: {{ $detailOrder->weight ?? '0' }} gr</div>
                </td>
                <td class="small text-end">{{ $detailOrder->quantity }}</td>
                <td class="small text-end">{{ 'Rp. ' . number_format($detailOrder->value, 0, '.', '.') }}</td>
                <td class="small text-end">{{ 'Rp. ' . number_format($detailOrder->quantity * $detailOrder->value, 0, '.', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <table class="w-100">
          <tbody>
            <tr>
              <td class="small text-end">Total Harga Barang</td>
              <td class="small text-end">{{ 'Rp. ' . number_format($order->total_harga_barang, 0, '.', '.') }}</td>
            </tr>
            <tr>
              <td class="small text-end">Total Ongkos Kirim</td>
              <td class="small text-end">{{ 'Rp. ' . number_format($order->courier_price, 0, '.', '.') }}</td>
            </tr>
            <tr>
              <td class="small text-end">Total Belanja</td>
              <td class="small text-end">{{ 'Rp. ' . number_format($order->jumlah_total, 0, '.', '.') }}</td>
            </tr>
            <tr>
              <td class="small text-end fw-bold">Total Tagihan</td>
              <td class="small text-end fw-bold">
                <div class="fw-bold">{{ 'Rp. ' . number_format($order->total_tagihan, 0, '.', '.') }}</div>
                @if($order->coin_yang_digunakan > 0) <div class="text-success small">(Potongan Onelito Coins {{ 'Rp. ' . number_format($order->coin_yang_digunakan, 0, '.', '.') }})</div> @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

  <script>
    function printWithoutNavbar() {
      const navbar = document.getElementById('navbar');
      navbar.style.display = 'none';
      window.print();
      navbar.style.display = '';
    }
  </script>
</body>
</html>