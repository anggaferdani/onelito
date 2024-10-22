<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- boxicons CSS --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.1/dist/flowbite.min.css" />



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

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

    <style>
        /* On screens that are 992px or less, set the background color to blue */
        @media screen and (min-width: 601px) {
            .res {
                display: none;
            }
        }

        /* On screens that are 600px or less, set the background color to olive */
        @media screen and (max-width: 600px) {
            .web {
                display: none;
            }
        }
    </style>
    @php
        $auth = Auth::guard('member')->user();

        $request = Request::input('section', null);
        $imgProfile = url('img/foto.png');

        if ($auth->profile_pic !== null) {
            $imgProfile = url('storage/'.$auth->profile_pic);
        }

        $details = $order->details;

        $previous = url()->previous();
    @endphp
    <div class="res">
        <div class="container-fluid py-3">
            <div class="fixed-top p-4 bg-white">
                <div class="container mb-3">
                    <div class="d-flex">
                        <a href="/profil2" class="{{ $title === 'profil2' ? 'active' : '' }}">
                            <div class="d-flex">
                                <!-- <i class="fa-solid fa-circle-user mr-4" style="font-size: xx-large"></i> -->
                                <img src="{{ $imgProfile }}" class="mr-4" style="width:32px;height:32px;border-radius:50%;max-width:unset">
                                <div>
                                    <h4 style="font-size: 15px" class="text-start">{{ $auth->nama }}</h4>
                                    <p style="font-size: 12px" class="text-start">{{ $auth->email }}</p>
                                </div>
                            </div>
                        </a>
                        <div class="ml-auto" style="font-size: 22px">
                            <a href="/"><i class='bx bx-x-circle text-danger' style="font-size: x-large"></i></a>
                        </div>
                    </div>
                </div>
                <div class="container overflow-scroll">
                    <div class="d-flex" style="width: 92vw">
                        <a href="#" style="font-size: 11px" class="btn btn-outline-secondary rounded-pill mr-2 ">
                            <i class='bx bx-menu-alt-left'></i>
                            Filter</a>
                        <a href="/profil?section=cart" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'Shopping Cart' ? 'active' : '' }}">Shopping
                            cart</a>

                        <a href="/profil?section=wishlist" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'wishlist' ? 'active' : '' }}">WishList</a>

                        <a href="/profil?section=purchase" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'purchase' ? 'active' : '' }}">Purchase
                            history</a>

                    </div>
                </div>
            </div>

            <div style="margin-top: 17vh; margin-bottom: 10vh">
            <!-- <div class="card"> -->
                <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <div class="container p-0 mt-1">
                                        <!-- <h5><i class="fa-solid fa-cart-shopping"></i> <b>Shopping cart</b></h5> -->
                                        <div class="alert alert-success" role="alert">
                                        Terima kasih atas pesanannya, order anda sedang diproses dan admin kami akan segera menghubungi anda by WhatsApp, mohon ditunggu.
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="container p-0">
                                        <h5 class="mb-3"><i class="fa-solid"></i> <b>Invoice #{{ $order->no_order }}</b></h5>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mx-3 table-responsive">
                                        <table class="table" style="width:700px">
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
                                                        <td width="8%">{{ $detail->jumlah_produk }}</td>
                                                        <td width="20%">Rp. {{ number_format($cartable->harga, 0, '.', '.') }}</td>
                                                        <td width="20%">Rp. {{ number_format($detail->total, 0, '.', '.') }}</td>
                                                    </tr>    
                                                @endif

                                                @if ($detail->productable_type === "KoiStock")
                                                    <tr>
                                                        <td width="52%">{{ "$cartable->variety | $cartable->breeder | $cartable->size | $cartable->bloodline" }}</td>
                                                        <td>{{ $detail->jumlah_produk }}</td>
                                                        <td>Rp. {{ number_format($cartable->harga_ikan, 0, '.', '.') }}</td>
                                                        <td>Rp. {{ number_format($detail->total, 0, '.', '.') }}</td>
                                                    </tr>    
                                                @endif

                                            @empty
                                            @endforelse                    
                                        </tbody>
                                        </table>

                                    </div>
                                    <div class="row">
                                        <div class="col-11">
                                        <ul class="list-unstyled float-end me-0">
                                            <li><span class="me-3 float-start">Total Harga ({{ count($details ?? []) }} Barang):</span><i class=""></i>
                                                Rp. {{ number_format($order->total, 0, '.', '.') }}
                                            </li>
                                        </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                        <div class="container-fluid mt-3">
                            <div class="col-12">
                                <div class="float-end">
                                    <button onclick="pdf()" class="btn btn-success w-100 justify-content-between"> <i class="fa-solid fa-download"></i> Download pdf</button>
                                </div>
                            </div>
                        </div>
                </div>

            <!-- </div> -->
        </div>
    </div>

    <div class="web">
        <div class="container p-0">
            <a href="{{ $previous }}" class="text-dark" style="text-decoration: blink"><i
                    class="fa-solid fa-arrow-left text dark"></i> back</a>
            <br><br>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <button class="nav-link active bg-tranparent text-body"
                                        style="background-color: white" id="v-pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-home2" type="button" role="tab"
                                        aria-controls="v-pills-home2" aria-selected="true">
                                        <div class="row">
                                            <div class="col-2 p-0">
                                                <img src="{{ $imgProfile }}" style="width:48px;height:48px;border-radius:50%;max-width:unset">
                                            </div>
                                            <div class="col-10">
                                                <h4 class="m-0 ms-lg-3 text-md-start">{{ $auth->nama }}</h4>
                                                <p class="m-0 ms-lg-3 text-md-start">{{ $auth->email }}</p>
                                            </div>
                                        </div>
                                    </button>
                                    <br>
                                    <h5>Filter</h5>
                                    <a href="/profil?section=cart" class="nav-link bg-tranparent text-body p-2 text-lg-start"
                                        style="background-color: white;font-size:larger">
                                        Auction cart
                                    </a>
                                    <a href="/profil?section=store-cart" class="nav-link bg-tranparent text-body p-2 text-lg-start"
                                        style="background-color: white;font-size:larger">
                                        Store cart
                                    </a>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="nav card mt-3 mb-2 nav-pills">
                        <a class="btn btn-danger w-100 justify-content-between" role="button"
                            id="v-pills-password-tab" href="/profil?section=change-password"
                            style="font-size: x-large">Ganti Password</a>
                    </div>
                    <div class="card p-0">
                        <a class="btn btn-danger w-100 justify-content-between" href="/login" role="button"
                            style="font-size: x-large">Log Out</a>
                    </div> -->
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <div class="container p-0">
                                        <!-- <h5><i class="fa-solid fa-cart-shopping"></i> <b>Shopping cart</b></h5> -->
                                        <div class="alert alert-success" role="alert">
                                            Terima kasih atas pesanannya, order anda sedang diproses dan admin kami akan segera menghubungi anda by WhatsApp, mohon ditunggu.
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="container p-0">
                                        <h5 class="mb-3"><i class="fa-solid"></i> <b>Invoice #{{ $order->no_order }}</b></h5>
                                    </div>
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
                                                @if ($detail->productable_type == "Product")
                                                    <tr>
                                                        <td width="52%">{{$detail->productable->merek_produk}}-{{ $detail->productable->nama_produk }}</td>
                                                        <td>{{ $detail->jumlah_produk }}</td>
                                                        <td>Rp. {{ number_format($cartable->harga, 0, '.', '.') }}</td>
                                                        <td>Rp. {{ number_format($detail->total, 0, '.', '.') }}</td>
                                                    </tr>    
                                                @endif

                                                @if ($detail->productable_type == "KoiStock")
                                                    <tr>
                                                        <td width="52%">{{ "$cartable->variety | $cartable->breeder | $cartable->size | $cartable->bloodline" }}</td>
                                                        <td>{{ $detail->jumlah_produk }}</td>
                                                        <td>Rp. {{ number_format($cartable->harga_ikan, 0, '.', '.') }}</td>
                                                        <td>Rp. {{ number_format($detail->total, 0, '.', '.') }}</td>
                                                    </tr>    
                                                @endif

                                            @empty
                                            @endforelse                    
                                        </tbody>
                                        </table>

                                    </div>
                                    <div class="row">
                                        <div class="col-11">
                                        <ul class="list-unstyled float-end me-0">
                                            <li><span class="me-3 float-start">Total Harga ({{ count($details ?? []) }} Barang):</span><i class=""></i>
                                                Rp. {{ number_format($order->total, 0, '.', '.') }}
                                            </li>
                                        </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                        <div class="container-fluid mt-3">
                            <div class="col-12">
                                <div class="float-end">
                                    <button onclick="pdf()" class="btn btn-success w-100 justify-content-between"> <i class="fa-solid fa-download"></i> Download pdf</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script type="text/javascript">
        function openPage(event, page) {
            event.preventDefault();

            pageContent = document.getElementsByClassName("pageContent");
            for (var i = 0; i < pageContent.length; i++) {
                pageContent[i].style.display = "none"
            }
            var tot = document.getElementById(page).style.display = "block"
            var aktif = document.getElementsByClassName("cekAktive");
            for (var i = 0; i < aktif.length; i++) {
                aktif[i].className = aktif[i].className.replace(" active", "")
            }

            event.currentTarget.className += " active";
            // console.log(tot)
        }
    </script> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>
<script>
    let id = '{{ $order->id_order }}';
    function pdf() {
        window.open(`/carts-order/${id}/pdf`, '_blank');
    }
</script>
