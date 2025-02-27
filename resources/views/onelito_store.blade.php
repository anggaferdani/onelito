@extends('layout.main')

@section('container')
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

        .filter-items {
            display: inline-block;
        }

        #filter-tab {
            display: none;
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

        @media screen and (min-width: 601px) and (max-width: 991px) {
            /* #filter-tab {
                display: block;
            } */

            .nav-atas {
                display: block
            }

            .nav-samping {
                display: none;
            }
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #F0F0F0;
        }

        .nav-pills {
            overflow: auto;
            white-space: nowrap;
            flex-wrap: unset !important;
        }

        .cart {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-direction: column;
        }

        .cb-judul {
            height: 3.5rem;
            font-size: 0.9rem;
        }

        .order-now {
            margin-left: 8px;
        }

        @media (min-width: 1400px) {
            .order-now {
                margin-left: 16px;
            }
        }

        @media (max-width: 575.98px) {
            .order-now {
                margin-left: 13px;
            }

            .btn-group {
                margin-right: 0; /* Remove margin between buttons on smaller screens */
                margin-bottom: 5px; /* Add spacing below buttons on smaller screens */
            }

            /* Adjust button size and padding for smaller screens */
            .btn {
                padding: 0.2rem 0.5rem; /* Adjust padding to make buttons smaller */
            }

            .me-2 {
                margin-right: 0.3rem !important;
            }
        }

        #searchInput {
            background-position: 10px 12px;
            background-repeat: no-repeat;
            width: 300px;
            font-size: 16px;
            padding: 12px 20px 12px 20px;
            border: 1px solid #ddd;
            margin-bottom: 12px;
        }

        .search-container {
            margin-top: 15px;
        }

        .search-container button {
            float: left;
            padding: 11px 11px 11px 11px;
            margin-bottom: 12px;
            margin-right: 5px;
            background: #ddd;
            font-size: 18px;
            border: none;
            cursor: pointer;
        }

        .topnav .search-container button:hover {
            background: #ccc;
        }
    </style>
    <br><br><br><br>
    <div id="carouselExampleControls" class="pt-2 carousel slide" data-bs-interval="3000" data-bs-ride="carousel">
        <div class="carousel-inner img-mh-300">
            @forelse($banners as $key => $val)
                @php
                    $banner = 'img/new-banner.jpg';

                    if ($val->banner !== null) {
                        $bannerImg = url('storage') . '/' . $val->banner;
                    }
                @endphp

                @if ($val->banner !== null)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="container-fluit" style="background-color:red;">
                            <img src="{{ $bannerImg }}" class="w-100" alt="...">
                        </div>
                    </div>
                @endif
            @empty
            @endforelse

            @forelse($auctions as $key => $auction)
                @php
                    $bannerImg = 'img/event.png';

                    if ($auction->banner !== null) {
                        $bannerImg = url('storage') . '/' . $auction->banner;
                    }
                @endphp

                @if ($auction->banner !== null)
                    <div class="carousel-item {{ $key === 0 ? '' : '' }}">
                        <div class="container-fluit" style="background-color:red;">
                            <img src="{{ $bannerImg }}" class="w-100" alt="...">
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
            {{-- <div class="carousel-item active">
                <div class="container-fluit" style="background-color:red;">
                    <img src="img/new-banner.jpg" class="d-block w-100" alt="Frame">
                </div>
            </div> --}}
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="container" style="min-height:400px">

        <br>

        @php
            $auth = Auth::guard('member')->user();


            if ($auth !== null) {
                $auth = true;
            }

            $kategoriTitle = 'All Product';

            if ($kategori) {
                $kategoriTitle = $category->nama_kategori;
            }


            $search = request()->search;
        @endphp

        <!-- <a id="filter-tab" class="float">
            <i class="fa fa-fukter my-float">Filter</i>
        </a> -->
        <button type="button" id="filter-tab" class="btn btn-outline-secondary rounded-pill mr-2"><i
                class='bx bx-menu-alt-left'></i> Filter</button>

        <div class="row gx-3">
            {{-- On screens that are 992px or less, set the display on --}}
            <div class="col-3 nav-samping">
                <div class="">
                    <div class="filter-box card text-dark bg-light mb-3 mt-3" style="max-width: 18rem;">
                        <div class="card-header"><i class='bx bx-menu-alt-left'></i> Etalase Toko</div>
                        <div class="card-body">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <a href="{{ url('/onelito_store') }}"
                                    class="nav-link bg-tranparent text-body m-2 {{ $kategori == null ? 'active' : '' }}"
                                    style="text-align:left" id="v-pills-Semua-tab" role="tab"
                                    aria-controls="v-pills-Semua" aria-selected="true">All
                                    Product</a>

                                @foreach ($productCategories as $cat)
                                    <a href="{{ url('/onelito_store?kategori=' . $cat->id_kategori_produk) }}"
                                        class="nav-link bg-tranparent text-body m-2 {{ $kategori == $cat->id_kategori_produk ? 'active' : '' }}"
                                        style="text-align:left"
                                        id="v-pills-{{ $cat->kategori_produk }}-tab" type="button" role="tab"
                                        aria-controls="v-pills-{{ $cat->kategori_produk }}"
                                        aria-selected="false">{{ $cat->kategori_produk }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- On screens that are 600px or less, set the display none --}}
            <div class="container nav-atas overflow-auto">
                <div class="d-flex nav nav-pills" id="v-pills-tab" role="tablist">
                    <button type="button" id="filter-mobile" class="btn btn-outline-secondary rounded-pill"><i
                            class='bx bx-menu-alt-left'></i> Filter</button>
                    <div id="filter-container" class="d-flex gap-1 ms-1">
                        <button onclick="window.location.href='/onelito_store'" type="button"
                            class="filter-items btn btn-outline-secondary rounded-pill mr-2" id="v-pills-Semua-tab" type="button"
                            role="tab" aria-controls="v-pills-Semua" aria-selected="true">All Product</button>

                        @foreach ($productCategories as $cat)
                            <button onclick="window.location.href='/onelito_store?kategori={{ $cat->id_kategori_produk }}'"
                                type="button" class="filter-items btn btn-outline-secondary rounded-pill mr-2"
                                id="v-pills-{{ $cat->kategori_produk }}-tab" type="button" role="tab"
                                aria-controls="v-pills-{{ $cat->kategori_produk }}"
                                aria-selected="false">{{ $cat->kategori_produk }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-Semua" role="tabpanel"
                            aria-labelledby="v-pills-Semua-tab">
                            <div class="search-container">
                                <form id="search" action="{{ url()->full() }}" method="GET">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                    <input type="input" id="searchInput" name="search" placeholder="Cari Produk"
                                        title="Cari Produk" value="{{ $search }}">
                                    @if ($kategori !== null)
                                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                                    @endif
                                </form>
                            </div>
                            <div class="container mt-3">
                                <h5><b>{{ $kategoriTitle }}</b></h5>
                            </div>
                            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                                @forelse ($products as $product)
                                    @php
                                        $productPhoto = 'img/produk1.jpeg';

                                        if ($product->photo !== null) {
                                            $productPhoto = url('storage') . '/' . $product->photo->path_foto;
                                        }

                                        $wishlistClass = 'far fa-heart';

                                        if (array_key_exists('wishlist', $product->toArray()) && $product->wishlist !== null) {
                                            $wishlistClass = 'fas fa-heart';
                                        }
                                    @endphp
                                    <div class="col">
                                        <div class="border bg-light cart">
                                            <a href="/onelito_store/{{ $product->id_produk }}"><img
                                                    src="{{ $productPhoto }}" alt="bio media" class="card-img-top"
                                                    height="170"></a>
                                            <div class="container px-2">
                                                <div class="cb-judul">
                                                    <p>{!! Illuminate\Support\Str::limit("$product->merek_produk $product->nama_produk", 35) !!}</p>
                                                </div>
                                                <p><b>Rp. {{ number_format($product->harga, 0, '.', '.') }}</b></p>
                                            </div>
                                            {{-- <div class="col px-2 mb-2" style="text-align: end">
                                                <button class="border rounded-1 text-black-50"
                                                    style="background-color: transparent;font-size:small">
                                                    <i
                                                        data-id="{{ $product->id_produk }}"
                                                        class="{{ $wishlistClass }} wishlist produk-{{ $product->id_produk }}"></i>
                                                    <span>Wishlist</span></button>
                                            </div> --}}
                                            <div class="mb-1">
                                                <button class="border-0 btn-success rounded-2 order-now d-none"
                                                    data-id="{{ $product->id_produk }}"
                                                    data-price="{{ $product->harga }}"
                                                    style="background-color:#188518;">Order

                                                    Now</button>
                                            </div>
                                            <div class="col-12">
                                                <div class="row px-1">
                                                    <div class="col-6">
                                                        <button class="border rounded-1 text-black-50 button-wishlist"
                                                            data-id="{{ $product->id_produk }}"
                                                            style="background-color: transparent;font-size:9px"><i
                                                                class="{{ $wishlistClass }} wishlist produk-{{ $product->id_produk }}"></i>
                                                            <span>Wishlist</span></button>
                                                    </div>
                                                    <div class="col-6 mb-1 text-end">
                                                        @if ($product->stock == 0)
                                                            <button class="rounded text-white"
                                                                style="font-size: 9px; background-color: red;border-color:red; outline: none; border: none;">stock
                                                                habis</button>
                                                        @else
                                                            <button class="rounded addcart"
                                                                data-id="{{ $product->id_produk }}"
                                                                style="background-color: red;border-color:red; outline: none; border: none; font-size:x-small"><i
                                                                    class="fa-solid fa-cart-shopping"
                                                                    style="color: white"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Product Found</td>
                                    </tr>
                                @endforelse
                            </div>
                            @php
                                $oriPrev = $products->previousPageUrl();
                                $oriNext = $products->nextPageUrl();

                                $prev = $products->previousPageUrl();
                                $next = $products->nextPageUrl();
                                $page = '';

                                if ($kategori !== null) {
                                    $prev .= '&kategori=' . $kategori;
                                    $next .= '&kategori=' . $kategori;
                                    $page .= "&kategori=$kategori";
                                }

                                if ($search !== null) {
                                    $prev .= '&search=' . $search;
                                    $next .= '&search=' . $search;
                                    $page .= "&search=$search";
                                }

                                if ($oriPrev == null) {
                                    $prev = '#';
                                }

                                if ($oriNext == null) {
                                    $next = '#';
                                }
                            @endphp

                            <div class="btn-toolbar my-3 justify-content-end" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <div class="btn-group me-2" role="group" aria-label="First group">
                                    @if ($products->onFirstPage())
                                        <button type="button" class="btn btn-danger disabled">Prev</button>
                                    @else
                                        <a href="{{ $prev }}"><button type="button"
                                                class="btn btn-danger">Prev</button></a>
                                    @endif
                                </div>

                                @foreach ($products->onEachSide(0)->links()->elements as $elements)
                                    @if (is_array($elements))
                                        @foreach ($elements as $key => $element)
                                            <div class="btn-group me-2" role="group" aria-label="First group">
                                                <a href="?page={{ $key . $page }}"><button type="button"
                                                        class="btn btn-danger {{ (request()->page ?? 1) == $key ? 'active disabled' : '' }}"">{{ $key }}</button></a>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach

                                <div class="btn-group me-2" role="group" aria-label="First group">
                                    @if ($products->hasMorePages())
                                        <a href="{{ $next }}"><button type="button"
                                                class="btn btn-danger">Next</button></a>
                                    @else
                                        <button type="button" class="btn btn-danger disabled">Next</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
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
    // Add this:
    let filterVisible = true; // Initialize the filter as VISIBLE

    $(document).on('click', '#filter-mobile', function(e) {
        var filterContainer = $('#filter-container');

        if (filterVisible) {
            filterContainer.addClass('d-none');
            filterVisible = false; // Update the state to HIDDEN
        } else {
            filterContainer.removeClass('d-none');
            filterVisible = true;  // Update the state to VISIBLE
        }
    });

    $(document).on('click', '#filter-tab', function(e) {
        var $this = $(this);

        $this.toggleClass('is-checked');

        if ($this.is('.is-checked')) {
            $('.filter-box').addClass('d-none');

        } else {
            $('.filter-box').removeClass('d-none');
        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    $(document).on('click', '.button-wishlist', function(e) {
        var element = $(e.currentTarget);
        var id = element.attr('data-id');
        var children = document.getElementsByClassName(`wishlist produk-${id}`)[0];
        var elClass = children.getAttribute('class');
        var targetClass = elClass.substr(0, 21);
        var idClass = elClass.substr(22);
        var targetElements = $(`.${idClass}`)

        if (targetClass === 'far fa-heart wishlist') {
            $.ajax({
                type: 'POST',
                url: `wishlists`,
                data: {
                    id_produk: id
                },
                dataType: "json",
                success: function(res) {
                    $.map(targetElements, function(item) {
                        $(item).attr('class', `fas fa-heart wishlist ${idClass}`);
                    })

                    return true;
                },
                error: function(error) {
                    console.log(error)
                    return false
                }
            })
        }

        if (targetClass === 'fas fa-heart wishlist') {
            $.ajax({
                type: 'DELETE',
                url: `wishlists/${id}`,
                data: {
                    id_produk: id
                },
                dataType: "json",
                success: function(res) {
                    $.map(targetElements, function(item) {
                        $(item).attr('class', `far fa-heart wishlist ${idClass}`);
                    })

                    return true;
                },
                error: function(error) {
                    console.log(error)
                    return false
                }
            })
        }
    })

    $(document).on('click', '.addcart', function(e) {
        var button = $(this);
        var productId = $(this).attr('data-id')
        // $(this).attr('disabled', true)
        // var output = document.querySelector("#output");
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
                jumlah: 1,
                cartable_id: productId,
                cartable_type: 'Product',
            },
            dataType: "json",
            complete: function(res) {
                // document.location = '/profil?section=store-cart'
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
                            document.location = '/cart'
                        } else {
                            document.location = '/cart'
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

    function thousandSeparator(x) {
        var reverse = x.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return ribuan
    }

    $(document).on('click', '.order-now', function(e) {
        var nominal = $(this).attr('data-price');
        nominal = thousandSeparator(nominal)

        if (user == null) {
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

        if (user == true) {

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
                    orderNowProcess(this);
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

    })

    function orderNowProcess(element) {
        var nominal = $(element).attr('data-price');
        const output = 1;
        var totalPrice = 0;
        var items = 0;
        var transaction = $('.transaction')
        var dataOrder = []

        var orderItem = {}

        var id = $(element).attr('data-id');

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