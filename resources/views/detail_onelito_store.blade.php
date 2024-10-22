@extends('layout.main')

@section('container')
<br><br><br><br>
    <div class="container">
        <br>
        <a href="/onelito_store"><i class="fa-solid fa-arrow-left-long text-body"></i></a>
        <br><br>

        <div class="container">
            <style>
                .swal2-cancel {
                    margin-right: 10px;
                }

                .swal2-cancel {
                    background-color: #dc3545;
                }

                .swal2-confirm {
                    background-color: #198754;
                }
                /* On screens that are 992px or less, set the background color to blue */
                @media screen and (min-width: 601px) {
                    .nav-atas {
                        display: none
                    }
                }

                /* On screens that are 600px or less, set the background color to olive */
                @media screen and (max-width: 600px) {
                    .nav-samping {
                        display: none;
                    }
                }
            </style>
            @php
                $imgUrl = 'img/bio_media.png';

                if ($product->photo !== null) {
                    $imgUrl = 'storage/' . $product->photo->path_foto;
                }
            @endphp
            <div class="nav-atas">

                <div class=>
                    <img src="{{ url($imgUrl) }}" alt="bio media" class="w-100">
                </div>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-10">
                                <p>{{ "$product->merek_produk $product->nama_produk" }}</p>
                            </div>
                            <div class="col-2">
                                <i class="far fa-heart m-3" style="font-size:large"></i>
                            </div>
                        </div>

                        <h2>Rp. {{ $product->harga }}</h2>
                        <hr>
                        <p class="alert-link text-danger">Detail</p>
                        <p>{!! $product->deskripsi !!}</p>
                    </div>
                    <div>
                        <h5 class="card-title">Ordered quantity</h5>

                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <button type="button" class="btn btn-light"><i class="fa-solid fa-minus"></i></button>
                            <button type="button" class="btn btn-light">1</button>
                            <button type="button" class="btn btn-light"><i class="fa-solid fa-plus"></i></button>
                        </div>

                        <div class="row">
                            <div class="col">
                                <h6 class="my-md-2 text-muted">Subtotal</h6>
                            </div>
                            <div class="col">
                                <p>Rp 1.300.000</p>
                            </div>
                        </div>
                        <div class="row gx-5 mt-3">
                            <div class="col">
                                <button type="button" class="btn btn-success w-100 justify-content-between text-success addcart"
                                    style="background-color: white">Add Cart</button>
                            </div>
                            <div class="col">
                                <button type="button" id="wishlist" class="btn btn-success w-100 justify-content-between text-success"
                                    style="background-color: white">Wishlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-samping">
                <div class="row">
                    <div class="col">
                        <img src="{{ url($imgUrl) }}" alt="bio media" class="w-100">
                    </div>
                    <div class="col">
                        <p style="font-size: 35px">{{ "$product->merek_produk $product->nama_produk" }}</p>
                        <h2>Rp {{ number_format($product->harga, 0, '.', '.') }}</h2>
                        <hr>
                        <p class="alert-link text-danger">Detail</p>
                        <p>{!! $product->deskripsi !!}</p>
                    </div>
                    <div class="col">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Ordered quantity</h5>

                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <button type="button" id="subtract" class="btn btn-light"><i
                                            class="fa-solid fa-minus"></i></button>
                                    <button type="button" id="output" class="btn btn-light">1</button>
                                    <button type="button" id="add" class="btn btn-light"><i
                                            class="fa-solid fa-plus"></i></button>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <h6 class="my-md-2 text-muted">Subtotal</h6>
                                    </div>
                                    <div class="col">
                                        <p id="total_price" class="total-price">Rp {{ number_format($product->harga, 0, '.', '.') }}</p>
                                    </div>
                                </div>
                                @auth('member')

                                <button type="button"
                                onclick="orderNow()"
                                class="btn btn-success w-100 justify-content-between mb-xl-2 d-none">Order
                                    Now</button>
                                @endauth

                                @guest('member')
                                <button type="button"
                                    onclick="loginNow()"
                                    class="btn btn-success w-100 justify-content-between mb-xl-2 d-none">Order
                                    Now</button>
                                @endguest
                                <div class="row gx-5">
                                    <div class="col">
                                        <button type="button"
                                            id="addcart"
                                            class="btn btn-success w-100 justify-content-between text-success addcart"
                                            style="background-color: white">Add Cart</button>
                                    </div>
                                    <div class="col">
                                        <button type="button"
                                            id="wishlist"
                                            class="btn btn-success w-100 justify-content-between text-success"
                                            style="background-color: white">Wishlist</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
    </div>
@endsection
@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let user = @json($auth);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function isMobile() {
            const regex = /Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
            return regex.test(navigator.userAgent);
        }

        let add = document.querySelector("#add");
        let output = Number(document.querySelector("#output").innerText);

        let productId = Number('{{ $product->id_produk }}');
        let originPrice = Number('{{ $product->harga }}');

        // output * originPrice
        let totalPrice = Number('{{ $product->harga }}');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        add.addEventListener("click", function() {
            let output = document.querySelector("#output");
            let result = Number(output.innerText) + 1;
            var priceTotal = originPrice * result;

            output.innerText = result;
            document.querySelector("#total_price").innerText = "Rp "+thousandSeparator(priceTotal);
        });

        let kurang = document.querySelector("#subtract");

        kurang.addEventListener("click", function() {
            let output = document.querySelector("#output");
            let result = Number(output.innerText) - 1;

            if (result < 1) {
                result = 1
            }

            var priceTotal = originPrice * result;
            output.innerText = result;
            document.querySelector("#total_price").innerText = "Rp "+thousandSeparator(priceTotal);

        });

        $(document).on('click', '.addcart', function(e) {
            var button = $(this);
            $(this).attr('disabled', true)
            var output = document.querySelector("#output");

            if (user == null) {
                swalWithBootstrapButtons.fire({
                    title: 'Belum Login',
                    text: `Harap login terlebih dulu untuk pemesanan`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ke halaman login',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = '/login';

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

                return true;
            }

            $.ajax({
                type: 'POST',
                url: `/carts`,
                data: {
                    jumlah: Number(output.innerText),
                    cartable_id: productId,
                    cartable_type: 'Product',
                },
                dataType: "json",
                complete: function(res) {
                    swalWithBootstrapButtons.fire({
                        title: 'Product berhasil ditambahkan',
                        text: `Klik Ya, untuk lihat keranjang`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (isMobile()) {
                                document.location = '/storecart'
                            } else {
                                document.location = '/profil?section=store-cart'
                            }

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
                },
                error: function(error) {
                    console.log(error)
                    return false
                }
            })
        });

        $(document).on('click', '#wishlist', function(e) {
            var button = $(this);
            $.ajax({
                    type: 'POST',
                    url: `/wishlists`,
                    data: {
                        id_produk: productId
                    },
                    dataType: "json",
                    success: function(res) {
                        document.location = '/wishlistlog'

                        return true;
                    },
                    error: function(error) {
                        console.log(error)
                        return false
                    }
            })
        });

        function thousandSeparator(x) {
            var	reverse = x.toString().split('').reverse().join(''),
            ribuan 	= reverse.match(/\d{1,3}/g);
            ribuan	= ribuan.join('.').split('').reverse().join('');

            return ribuan
        }

        function orderNow() {
            var nominal = $('.total-price')[0].innerText

            swalWithBootstrapButtons.fire({
                title: 'apa anda yakin?',
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

        function loginNow() {

            swalWithBootstrapButtons.fire({
                title: 'Belum Login',
                text: `Anda harus login terlebih dahulu untuk dapat melakukan order`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ke halaman login',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '/login';

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

        function orderNowProcess(element) {
            var nominal = $(element).attr('data-price');
            const output = 1;
            var totalPrice = 0;
            var items = 0;
            var transaction = $('.transaction')
            var dataOrder = []

            var orderItem = {}

            var id = productId;


            // orderItem.id = id;

            location.href = `/order-now?item=${id}`;
            // orderItem.price = nominal;

            // items += Number(output)
            // orderItem.price = nominal * Number(output)
            // orderItem.type = 'Product';
            // orderItem.total_produk = Number(output);

            // dataOrder.push(orderItem);

            // $.ajax({
            //     type: 'GET',
            //     url: `/order-now`,
            //     data: {
            //         // data_order: dataOrder,
            //         // total: nominal * Number(output),
            //         // item: items,
            //         item: dataOrder
            //     },
            //     dataType: "json",
            //     success: function(res) {
            //         location.href = `/carts/${res.id}`;
            //     },
            //     error: function(error) {
            //         // console.log(error)
            //         return false
            //     }
            // })
        }
    </script>
@endpush
