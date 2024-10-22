<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

    <title>ONELITO KOI</title>

    <style>
        @media screen and (max-width: 600px) {
            .font-footer {
                font-size:
            }

            a {
                font-size: 10px !important;
            }

            h1 {
                font-size: 17px !important;
            }

            h4 {
                font-size: 12px !important;
            }

            h5 {
                font-size: 11px !important;
            }

            h6 {
                font-size: 10px !important;
            }

            p {
                font-size: 10px !important;
            }
        }
    </style>
</head>

<body>
    @php
            $auth = Auth::guard('member')->user();

            $request = Request::input('section', null);
            $imgProfile = url('img/foto.png');

            if ($auth->profile_pic !== null) {
                $imgProfile = url('storage/'.$auth->profile_pic);
            }

            $order->load('details.productable');
            $details = $order->details;

            $previous = url()->previous();

            if(!str_contains($previous, '/onelito_store') && !str_contains($previous, '/koi_stok')) {
                $previous = '/';
            }
    @endphp
    <div class="row mt-3" style="margin-bottom:50px">
        <p style="font-size: 30px; text-align:center">Terimakasih atas pembelianya</p>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="container-fluid">

                <ul class="list-unstyled">
                    <!-- <li class="text-black"><h5><b>Nama: John Doe</b></h5></li> -->
                </ul>
            </div>
        </div>
    </div>
    </br>
    <div class="row">   
        <div class="col-md-8">
            <div class="tab-content" id="v-pills-tabContent">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="container p-0">
                                <!-- <h5><i class="fa-solid fa-cart-shopping"></i> <b>Shopping cart</b></h5> -->
                                <!-- <div class="alert alert-success" role="alert">
                                    Terima kasih atas pesanannya, saat ini order anda sedang di proses oleh admin kami, mohon ditunggu.
                                </div> -->
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col">
                            <!-- <div class="container p-0"> -->
                                <h5 class="mb-3"><i class="fa-solid"></i> <b>Invoice #{{ $order->no_order }}</b></h5>
                            <!-- </div> -->
                        </div>
                        
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mx-3">
                                <table class="table table-responsive">
                                <thead>
                                    <tr>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($details as $detail)
                                        @php
                                            $cartable = $detail->productable;
                                        @endphp
                                        @if ($detail->productable_type === "Product")
                                            <tr>
                                                <td width="52%">{{$detail->productable->merek_produk}}-{{ $detail->productable->nama_produk }}</td>
                                                <td>{{ $detail->jumlah_produk }}</td>
                                                <td>Rp. {{ number_format($detail->productable->harga, 0, '.', '.') }}</td>
                                                <td>Rp. {{ number_format($detail->total, 0, '.', '.') }}</td>
                                            </tr>    
                                        @endif

                                        @if ($detail->productable_type === "KoiStock")
                                            <tr>
                                                <td width="52%">{{ "$cartable->variety | $cartable->breeder | $cartable->size | $cartable->bloodline" }}</td>
                                                <td>{{ $detail->jumlah_produk }}</td>
                                                <td>Rp. {{ number_format($detail->productable->harga_ikan, 0, '.', '.') }}</td>
                                                <td>Rp. {{ number_format($detail->total, 0, '.', '.') }}</td>
                                            </tr>    
                                        @endif

                                    @empty
                                    @endforelse                    
                                </tbody>
                                </table>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                <ul class="list-unstyled me-0">
                                    <li>
                                    </li>
                                </ul>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
                <span class="me-3 float-start">Total Harga ({{ count($details ?? []) }} Barang):</span><i class=""></i>
                Rp. {{ number_format($order->total, 0, '.', '.') }}
                <div class="container-fluid mt-3">
                    <div class="col-12">
                        <div class="float-end">
                            <!-- <button onclick="pdf()" class="btn btn-success w-100 justify-content-between"> <i class="fa-solid fa-download"></i> Download pdf</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>