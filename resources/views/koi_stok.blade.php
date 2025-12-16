@extends('layout.main')

@section('container')
    <style>
        .cb-judul {
            height: 2.5rem;
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

        @media screen and (max-width: 600px) {
            .nav-samping {
                display: none;
            }

        }
    </style>

    @php
        $auth = Auth::guard('member')->user();

        if ($auth !== null) {
            $auth = true;
        }
    @endphp

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
                    <div class="carousel-item {{ $key === 0 ? '' : '' }}">
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
                            <img src="{{ $bannerImg }}" class="w-100" alt="..." loading="lazy">
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
            <div class="carousel-item active">
                <div class="container-fluit" style="background-color:red;">
                    <img src="img/new-banner.jpg" class="d-block w-100" alt="Frame">
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <br>

    <div class="container-fluit">
        <div class="container" style="min-height:400px;">
            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3 mb-5">
                @forelse($fishes as $fish)
                    @php
                        $photo = 'img/koi12.jpg';

                        $wishlistClass = 'far fa-heart';

                        if ($fish->foto_ikan !== null) {
                                $photo = url('storage') . '/' . $fish->foto_ikan;
                        }

                        if (array_key_exists('wishlist', $fish->toArray()) && $fish->wishlist !== null) {
                            $wishlistClass = 'fas fa-heart';
                        }

                    @endphp

                    <div class="col mt-5">
                        <div class="card">
                            <img src="{{ $photo }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <div class="cb-judul">
                                    <h5 class="card-title">{!! Illuminate\Support\Str::limit(
                                        "$fish->variety | $fish->breeder | $fish->size | $fish->bloodline",
                                        25,
                                    ) !!}</h5>
                                </div>
                                <p class="my-3" style="color :red">Rp. {{ number_format($fish->harga_ikan, 0, '.', '.') }}</p>
                                <div class="row">
                                    <div class="col-6 col-lg-6 px-1">
                                        <a target="_blank" href="https://wa.me/0811972857" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                            style="font-size: 70%">Question <span><i
                                                    class="fa-brands fa-whatsapp"></i></span></a>
                                    </div>
                                    <div class="col-6 col-lg-6 px-1">
                                        <a href="{{ url('koi_stok') . '/' . $fish->id_koi_stock }}"
                                            class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-0 px-lg-2"
                                            style="font-size: 70%">DETAIL <span><i
                                                    class="fa-solid fa-circle-chevron-right"></i></span></a>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="row">
                                        <div class="col-7 px-1">
                                        <button class="border rounded-1 text-black-50 button-wishlist"
                                            data-id="{{ $fish->id_koi_stock }}"

                                                            style="background-color: transparent;font-size:small"><i
                                                                class="{{ $wishlistClass }} wishlist produk-{{ $fish->id_koi_stock }}"></i>
                                                        <span>Wishlist</span></button>

                                        </div>
                                        <div class="col-5 px-1 text-end">
                                            @if($fish->stock == 0)
                                                <button class="rounded text-white" style="font-size: 12px; background-color: red;border-color:red; outline: none; border: none;">stock habis</button>
                                            @else
                                                <button class="rounded addcart" data-id="{{ $fish->id_koi_stock }}" style="background-color: red;border-color:red; outline: none; border: none;"><i class="fa-solid fa-cart-shopping" style="color: white"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse

                <!-- <div class="col-6 col-lg-3 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
            </div>
            <div class="btn-toolbar my-3 justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <a href="{{ $fishes->previousPageUrl() }}"><button type="button"
                            class="btn btn-danger">Prev</button></a>
                </div>
                @foreach ($fishes->onEachSide(0)->links()->elements as $elements)
                    @if (is_array($elements))
                        @foreach ($elements as $key => $element)
                            <div class="btn-group me-2" role="group" aria-label="First group">
                                <a href="?page={{ $key }}"><button type="button"
                                        class="btn btn-danger {{ (request()->page ?? 1) == $key ? 'active disabled' : '' }}"">{{ $key }}</button></a>
                            </div>
                        @endforeach
                    @endif
                @endforeach
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <a href="{{ $fishes->nextPageUrl() }}"><button type="button"
                            class="btn btn-danger">Next</button></a>
                </div>
            </div>
            <!-- <div class="row mb-5">
                                <div class="col-6 col-lg-3 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2 mt-5">
                                    <div class="card">
                                        <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <div class="cb-judul">
                                                <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farmee | Size | Farm</h5>
                                            </div>
                                            <p class="my-3" style="color :red">Rp. 7.500.000</p>
                                            <div class="row">
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="#" class="btn btn-danger w-100 d-flex justify-content-between p-1" style="font-size: 70%">Question <span><i class="fa-brands fa-whatsapp"></i></span></a>
                                                </div>
                                                <div class="col-6 col-lg-6 px-1">
                                                    <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between p-1 px-lg-2" style="font-size: 70%">DETAIL <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
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

            console.log(idClass)

            if (targetClass === 'far fa-heart wishlist') {
                $.ajax({
                    type: 'POST',
                    url: `wishlists`,
                    data: {
                        id_koi_stock: id
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
                        id_koi_stock: id
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
                    cartable_type: 'KoiStock',
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
</script>
@endpush
