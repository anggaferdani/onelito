<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

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

        .cb-judul {
            height: 3.5rem;
        }

        .swal2-cancel {
            margin-right: 10px;
        }

        .swal2-cancel {
            background-color: #dc3545;
        }

        .swal2-confirm {
            background-color: #198754;
        }

        .purchase-item {
            height: 14rem;
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
                /* display: none; */
            }

            .profil {
                display: none;
            }

            .tab-content {
                margin-top: -20%;
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

        $previous = url()->previous();

        if(!str_contains($previous, '/onelito_store') && !str_contains($previous, '/koi_stok')) {
            $previous = '/';
        }
    @endphp
    <div class="res">
        <div class="container-fluid py-3">
            <div class="fixed-top p-4 bg-white">
                <div class="container mb-3">
                    <div class="d-flex">
                        <a href="/profil" class="{{ $title === 'profil' ? 'active' : '' }}">
                            <div class="d-flex">
                                <i class="fa-solid fa-circle-user mr-4" style="font-size: xx-large"></i>
                                <div>
                                    <h4 style="font-size: 15px" class="text-start">{{ $auth->nama }}</h4>
                                    <p style="font-size: 12px" class="text-start">{{ $auth->email }}</p>
                                </div>
                            </div>
                        </a>
                        <div class="ml-auto" style="font-size: 22px">
                            <a href="{{ $previous }}"><i class='bx bx-x-circle text-danger' style="font-size: x-large"></i></a>
                        </div>
                    </div>
                </div>
                <div class="container overflow-scroll">
                    <div class="d-flex" style="width: 123vw">
                        <a href="#" style="font-size: 11px" class="btn btn-outline-secondary rounded-pill mr-2 ">
                            <i class='bx bx-menu-alt-left'></i>
                            Filter</a>
                        <a href="/shoppingcart" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'Shopping Cart' ? 'active' : '' }}">Shopping
                            cart</a>

                        <a href="/storecart" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'Store Cart' ? 'active' : '' }}">Store
                            cart</a>

                        <!-- <a href="/wishlist" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'wishlist' ? 'active' : '' }}">WishList</a> -->

                        {{-- <a href="/purchase" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'purchase' ? 'active' : '' }}">Purchase
                            history</a> --}}
                        <a href="/ganti_password" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'purchase' ? 'active' : '' }}">Ganti password</a>
                        <a href="/update_profile" style="font-size: 11px"
                            class="btn btn-outline-secondary rounded-pill mr-2 {{ $title === 'update-profile' ? 'active' : '' }}">Update Profile</a>
                    </div>
                </div>
            </div>

            <div style="margin-top: 17vh; margin-bottom: 10vh">
                @if ($title === 'profil')
                    @php
                        $imgProfile = url('img/foto.png');

                        if ($auth->profile_pic !== null) {
                            $imgProfile = url('storage/'.$auth->profile_pic);
                        }
                    @endphp
                    <div class="container my-3 text-center">
                        <h5><i class="fa-solid fa-user"></i> <strong>Profile</strong></h5>
                    </div>
                    <div class="container p-0">
                        <img src="{{ $imgProfile }}" style="width:351px;height:255px" class="card-img-top px-5" alt="image">
                        <div class="container text-center">
                            <button type="button" class="btn btn-light btn-sm">
                                <button onclick="uploadProfile()" class="border btn btn-light" style="width: 68vw">
                                    Change photo</button>
                            </button>
                            <!-- <button type="button" class="btn btn-light btn-sm">
                                <button onclick="updateProfile()" class="border btn btn-light" style="width: 68vw">
                                    Update Profile</button>
                            </button> -->
                        </div>
                        <div class="">
                            <div class="mb-3">
                                <p class="m-0">Name:</p>
                                <p><b>{{ $auth->nama }}</b></p>
                            </div>
                            <div class="mb-3">
                                <p class="m-0">Email:</p>
                                <p><b>{{ $auth->email }}</b></p>
                            </div>
                            <div class="mb-3">
                                <p class="m-0">Phone number:</p>
                                <p><b>{{ $auth->no_hp }}</b></p>
                            </div>
                            <div class="mb-3">
                                <p class="m-0">Address:</p>
                                <p><b>{{ $auth->alamat }}</b></p>
                            </div>
                        </div>
                    </div>
                @else
                    @yield('container')
                @endif
            </div>
        </div>
    </div>

    <div class="web">
        <div class="container p-0">
            <a href="/onelito_store" class="profil text-dark" style="text-decoration: blink"><i
                    class="fa-solid fa-arrow-left text dark"></i> back</a>
            <br><br>
            <div class="row">
                <div class="profil col-md-3">
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
                                            <div class="col-2">
                                                <!-- <i class="fa-solid fa-circle-user" style="font-size: xxx-large">
                                                </i> -->
                                                <img src="{{ $imgProfile }}" style="width:48px;height:48px;border-radius:50%;max-width:unset">

                                            </div>
                                            <div class="col-10 p-0 ps-2">
                                                <h4 class="m-0 ms-lg-3 text-md-start">{{ $auth->nama }}</h4>
                                                <p class="m-0 ms-lg-3 text-md-start">{{ $auth->email }}</p>
                                            </div>
                                        </div>
                                    </button>
                                    <br>
                                    <h5>Filter</h5>
                                    <button class="nav-link bg-tranparent text-body p-2 text-lg-start"
                                        style="background-color: white;font-size:larger" 
                                        id="v-pills-profile-tab"
                                        type="button"
                                        role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                        Auction cart
                                    </button>
                                    <button class="nav-link bg-tranparent text-body p-2 text-lg-start"
                                        style="background-color: white;font-size:larger" id="v-pills-profile-tab"
                                        data-bs-toggle="pill" data-bs-target="#v-pills-store" type="button"
                                        role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                        Store cart
                                    </button>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav card mt-3 mb-2 nav-pills">
                        <a class="btn btn-danger w-100 justify-content-between" role="button"
                            id="v-pills-password-tab" href="/profil?section=change-password"
                            style="font-size: x-large">Ganti Password</a>
                    </div>
                    <div class="nav card mb-2 nav-pills">
                        <a class="btn btn-danger w-100 justify-content-between" role="button"
                            id="v-pills-password-tab" href="/profil?section=update-profile"
                            style="font-size: x-large">Update Profile</a>
                    </div>
                    <div class="card p-0">
                        <a class="btn btn-danger w-100 justify-content-between" href="/logout" role="button"
                            style="font-size: x-large">Log Out</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane active"
                            id="v-pills-store" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="store container mt-3 my-3">
                                <h5><i class="fa-solid fa-cart-shopping"></i> <b>Store</b></h5>
                            </div>
                            <div class="container overflow-hidden p-0">
                                <div class="card">
                                    <div class="row p-2">
                                        <div class="col-lg-7 px-3 py-3">
                                            <!-- <div class="container d-flex py-3" style="">
                                                <input class="form-check-input mr-3 my-auto cart-check-all"
                                                    style="font-size:large;" type="checkbox" value=""
                                                    id="Pilih Semua">
                                                <label class="form-check-label" for="Pilih Semua">
                                                    Pilih Semua
                                                </label>
                                            </div> -->
                                            <div class="alert alert-info" role="alert">
                                              Klik Pesan Sekarang untuk melanjutkan pembelian.
                                            </div>
                                            <hr class="float-sm-end text-center" style="width: 98%;">
                                            @forelse($products as $cart)
                                                @php

                                                    $cartPhoto = url('');
                                                    $cartable = $cart;

                                                    if ($cart->photo !== null) {
                                                        $cartPhoto = url('storage') . '/' . $cart->photo->path_foto;
                                                    }

                                                    $cartPrice = $cartable->harga;
                                                @endphp


                                                    <div class="container">
                                                        <div class="container d-flex p-0 my-3">
                                                            <input class="form-check-input mr-3 my-auto cart-check d-none"
                                                                name="products_checkbox[]"
                                                                type="checkbox" data-price="{{ $cartPrice }}"
                                                                data-id="{{ $cart->id_produk }}"
                                                                data-type="product" value=""
                                                                id="flexCheckDefault">
                                                            <div class="card mr-3">
                                                                <a href="/onelito_store/{{ $cart->id_produk }}"><img
                                                                        src="{{ $cartPhoto }}"
                                                                        class="card-img-top"
                                                                        style="width: 5vw; object-fit: cover;"
                                                                        alt="..."></a>
                                                            </div>
                                                            <div>
                                                                <p class="m-0">{!! Illuminate\Support\Str::limit("$cartable->merek_produk $cartable->nama_produk", 75) !!}</p>
                                                                <p class="m-0"><b>Rp.
                                                                        {{ number_format($cartPrice, 0, '.', '.') }}</b>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="container d-flex p-0 my-3 justify-content-end">
                                                            <!-- <p class="my-auto text-danger">Tulis Catatan</p> -->
                                                            <p class="my-auto text-center d-none">
                                                                Pindahkan ke Wishlist |
                                                            </p>
                                                            <button class="border-0 mr-3 remove-cart"
                                                                data-id="{{ $cart->id_produk }}"
                                                                style="background-color: transparent"><i
                                                                class="fa-regular fa-trash-can"></i></button>
                                                            <div class="btn-group" role="group"
                                                                aria-label="Basic outlined example">
                                                                
                                                                <button type="button" id="subtract"
                                                                    onclick="manageProduct(this)"
                                                                    class="border-0 btn-light mr-2"
                                                                    style="background-color:tranparent">
                                                                    <i class="fa-sharp fa-solid fa-circle-minus text-black-50"
                                                                        style="font-size: larger"></i>
                                                                </button>
                                                                <button type="button" id="output"
                                                                    data-id="{{ $cart->id_produk }}"
                                                                    class="btn btn-light outputproduct outputproduct-{{ $cart->id_produk }}">1</button>
                                                                <button id="add" type="button"
                                                                    onclick="manageProduct(this)"
                                                                    class=" border-0 btn-light ml-2">
                                                                    <i class="fa-solid fa-circle-plus text-danger"
                                                                        style="font-size: larger"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="float-sm-end text-center mb-3" style="width: 98%;">
                                            @empty
                                            @endforelse
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="card">
                                                <div class="card-body ">
                                                    <h5 class="card-title mb-3">Ringkasan belanja</h5>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h6 class="card-subtitle text-muted">Total Barang (<span
                                                                    class="total-item">1</span> barang)
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h6 class="card-subtitle">Total harga</h6>
                                                        </div>
                                                        <div class="col">
                                                            <h6 class="card-subtitle text-muted text-end">Rp <span
                                                                    class="total-price">0</span></h6>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <a onclick="orderNow()"
                                                        class="transaction btn btn-danger w-100 justify-content-between "
                                                        href="#">Pesan
                                                        Sekarang (<span class="total-item">1</span>)</a>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                       
                                                <div class="mt-3">
                                                <a onclick=""
                                                                class="transaction btn btn-danger w-100 justify-content-between "
                                                                href="{{ url('/onelito_store').'?item='. request()->item }}">Belanja Produk Lainya</a>
                                                                <a onclick=""
                                                                class="d-none transaction btn btn-danger w-100 justify-content-between mt-3"
                                                                href="/cancel-order">Batalkan Pesanan</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/compressorjs/dist/compressor.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        checkProduct()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("body").on("click", ".remove-cart", function(o) {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'DELETE',
                url: `/order-now/${id}`,
                data: {},
                dataType: "json",
                success: function(res) {
                    if (res.count === 0) {
                        location.href = '/onelito_store';
                    } else {
                        // window.location.reload();
                        location.href = '/check-order-now';
                    }

                },
                error: function(error) {
                    return false
                }
            })
        })

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        function orderNow() {
            var nominal = $('.total-price')[0].innerText

            swalWithBootstrapButtons.fire({
                title: 'Apakah anda akan segera membeli produk ini?',
                text: `Total harga Rp. ${nominal}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    orderNowProcess();

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    // swalWithBootstrapButtons.fire(
                    //     'Batal',
                    //     'Pesanan dibatalkan',
                    //     'error'
                    // )
                }
            })
        }

        function orderNowProcess() {
            var boxes = document.getElementsByName("products_checkbox[]");
            var totalPrice = 0;
            var items = 0;
            var transaction = $('.transaction')
            var dataOrder = []

            for (var x = 0; x < boxes.length; x++) {
                var obj = boxes[x]
                var orderItem = {}

                    // if (obj.checked) {                        
                        var price = Number(obj.getAttribute('data-price'));
                        var id = Number(obj.getAttribute('data-id'));
                        var type = obj.getAttribute('data-type');

                        orderItem.id = id;
                        orderItem.price = price;


                      
                        var outputNumber = $('.outputproduct-' + id)[0].innerText
                        items += Number(outputNumber)
                        price = price * Number(outputNumber)
                        orderItem.price = price
                        orderItem.type = 'Product';
                        orderItem.total_produk = Number(outputNumber);

                        totalPrice += price;

                        dataOrder.push(orderItem);
                    // }
            }


            $.ajax({
                type: 'POST',
                url: `/carts-order?method=single`,
                data: {
                    data_order: dataOrder,
                    total: totalPrice,
                    item: items,
                },
                dataType: "json",
                success: function(res) {
                    location.href = `/carts/${res.id}`;
                    // swalWithBootstrapButtons.fire({
                    //     title: 'Pesanan',
                    //     text: `Pesanan akan segera diproses oleh admin`,
                    //     icon: 'success',
                    //     showCancelButton: false,
                    //     confirmButtonText: 'Ya',
                    //     reverseButtons: true
                    // }).then((result) => {
                    //     if (result.isConfirmed) {
                    //         location.reload();
                    //     } else if (
                    //         /* Read more about handling dismissals below */
                    //         result.dismiss === Swal.DismissReason.cancel
                    //     ) {

                    //     }
                    // })

                },
                error: function(error) {
                    // console.log(error)
                    return false
                }
            })
        }

        function manageProduct(e) {
            var elem = $(e);
            var idElem = elem.attr('id');

            var output = elem.closest('.btn-group').find('.outputproduct')[0]

            if (idElem === "add") {
                add(output);
                return true;
            }

            substract(output);
        }

        function substract(output) {
            let result = Number(output.innerText) - 1;
            var id = output.getAttribute('data-id')
            var boxes = document.getElementsByTagName("input");
            var totalPrice = 0;
            var items = 0;

            if (result < 1) {
                result = 1
            }

            output.innerText = result;
            checkProduct()

        }

        function add(output) {
            var boxes = document.getElementsByTagName("input");
            var totalPrice = 0;
            var items = 0;

            let result = Number(output.innerText) + 1;
            var id = output.getAttribute('data-id')
            output.innerText = result;
            checkProduct()

        }
        $("body").on("click", ".cart-check", function(o) {
            checkProduct()
        });

        $("body").on("click", ".cart-check-all", function(o) {
            var boxes = document.getElementsByTagName("input");
            var totalPrice = 0;
            var items = 0;
            var transaction = $('.transaction')
            var transClasses = transaction.attr('class').split(' ');

            for (var x = 0; x < boxes.length; x++) {
                var obj = boxes[x];

                if (obj.type == "checkbox") {
                    if (obj.name != "check")
                        obj.checked = o.target.checked;

                    if (obj.checked) {
                        var price = Number(obj.getAttribute('data-price'));
                        var id = Number(obj.getAttribute('data-id'));
                        var type = obj.getAttribute('data-type');

                        if (type === 'eventfish') {
                            items += 1
                        }

                        if (type === 'product' || type === 'koistock') {
                            var output = $('.outputproduct-' + id)[0].innerText
                            items += Number(output)
                            price = price * Number(output)
                        }

                        totalPrice += price;
                    }
                }
            }

            var elemTotalItem = $('.total-item')
            var elemTotalPrice = $('.total-price')

            elemTotalItem[0].innerText = items
            elemTotalItem[1].innerText = items

            totalPrice = thousandSeparator(totalPrice);

            elemTotalPrice[0].innerText = totalPrice


            if (items > 0) {
                // transaction.attr('onclick', 'orderNowProcess()')
                transaction.attr('onclick', 'orderNow()')
                transaction.removeClass('btn-secondary');
                transaction.addClass('btn-danger');
            }

            if (items === 0) {
                transaction.attr('onclick', '')
                transaction.removeClass('btn-danger');
                transaction.addClass('btn-secondary');
            }

        });

        $("body").on("click", ".cart-check-mobile", function(o) {
            checkProduct()
        });

        $("body").on("click", ".cart-check-mobile-all", function(o) {
            var boxes = document.getElementsByClassName("checkbox-mobile");
            var totalPrice = 0;
            var items = 0;
            var transaction = $('.transaction')
            var transClasses = transaction.attr('class').split(' ');


            for (var x = 0; x < boxes.length; x++) {
                var obj = boxes[x];

                if (obj.type == "checkbox") {
                    if (obj.name != "check")
                        obj.checked = o.target.checked;

                    if (obj.checked) {
                        var price = Number(obj.getAttribute('data-price'));
                        var id = Number(obj.getAttribute('data-id'));
                        var type = obj.getAttribute('data-type');

                        if (type === 'eventfish') {
                            items += 1
                        }

                        if (type === 'product' || type === 'koistock') {
                            var output = $('.outputproduct-' + id)[0].innerText
                            items += Number(output)
                            price = price * Number(output)
                        }

                        totalPrice += price;
                    }
                }
            }

            var elemTotalItem = $('.total-item')
            var elemTotalPrice = $('.total-price')

            elemTotalItem[0].innerText = items
            elemTotalItem[1].innerText = items

            totalPrice = thousandSeparator(totalPrice);

            elemTotalPrice[0].innerText = totalPrice


            if (items > 0) {
                // transaction.attr('onclick', 'orderNowProcess()')
                transaction.attr('onclick', 'orderNow()')
                transaction.removeClass('btn-secondary');
                transaction.addClass('btn-danger');
            }

            if (items === 0) {
                transaction.attr('onclick', '')
                transaction.removeClass('btn-danger');
                transaction.addClass('btn-secondary');
            }

        });

        function checkProduct() {
            var boxes = document.getElementsByTagName("input");
            var totalPrice = 0;
            var items = 0;
            var transaction = $('.transaction')

            for (var x = 0; x < boxes.length; x++) {
                var obj = boxes[x];
                
                var price = Number(obj.getAttribute('data-price'));
                var id = Number(obj.getAttribute('data-id'));
                var type = obj.getAttribute('data-type');

                if (obj.type == "checkbox") {
                    // if (obj.checked) {
                        var price = Number(obj.getAttribute('data-price'));
                        var id = Number(obj.getAttribute('data-id'));
                        var type = obj.getAttribute('data-type');

                        if (type === 'eventfish') {
                            items += 1
                        }

                        if (type === 'product' || type === 'koistock') {
                            var outputNumber = $('.outputproduct-' + id)[0].innerText
                            items += Number(outputNumber)
                            price = price * Number(outputNumber)
                        }

                        totalPrice += price;
                    // }
                }
            }



            var elemTotalItem = $('.total-item')
            var elemTotalPrice = $('.total-price')

            elemTotalItem[0].innerText = items
            elemTotalItem[1].innerText = items

            totalPrice = thousandSeparator(totalPrice);

            elemTotalPrice[0].innerText = totalPrice
        }

        function thousandSeparator(x) {
            // return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
            var reverse = x.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');

            return ribuan
        }

        function uploadProfile()
        {
            $("input#newProfilePhoto").trigger('click');
        }

        $(document).on("change", ".uploadProfileInput", function (e) {
            const file = e.target.files[0];

            if (!file) {
            return;
            }

            new Compressor(file, {
            quality: 0.6,
            convertSize: 900000,

            // The compression process is asynchronous,
            // which means you have to access the `result` in the `success` hook function.
            success(result) {
                // const formData = new FormData();
                const myFormData = new FormData();

                // The third parameter is required for server
                myFormData.append('foto', result, result.name);

                // myFormData.append('foto', $('.uploadProfileInput')[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: `/change-profile`,
                    processData: false, // important
                    contentType: false, // important
                    dataType : 'json',
                    data: myFormData,
                    success: function(res) {
                        location.reload()
                    },
                    error: function(error) {
                        // console.log(error)
                        return false
                    }
                })
            },
            error(err) {
                console.log(err.message);
            },
            });
        });
    </script>

</body>
