@extends('layout.mainlog')
@section('title', 'Onelito Store')
@section('container')
    <div class="container my-4">
        <style>
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

            .nav-pills .nav-link.active,
            .nav-pills .show>.nav-link {
                color: #fff;
                background-color: #F0F0F0;
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
                height: 6rem;

            }
        </style>
        <div class="row gx-3">
            {{-- On screens that are 992px or less, set the display on --}}
            <div class="col-3 nav-samping">
                <div class="">
                    <div class="card text-dark bg-light mb-3 position-fixed" style="max-width: 18rem;">
                        <div class="card-header"><i class='bx bx-menu-alt-left'></i> Etalase Toko</div>
                        <div class="card-body">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active bg-tranparent text-body m-2" style="text-align:left"
                                    id="v-pills-Semua-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Semua"
                                    type="button" role="tab" aria-controls="v-pills-Semua" aria-selected="true">All
                                    Product</button>
                                <button class="nav-link bg-tranparent text-body m-2" style="text-align:left"
                                    id="v-pills-makanan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-makanan"
                                    type="button" role="tab" aria-controls="v-pills-makanan" aria-selected="false">Fish
                                    Food</button>
                                <button class="nav-link bg-tranparent text-body m-2" style="text-align:left"
                                    id="v-pills-alat-tab" data-bs-toggle="pill" data-bs-target="#v-pills-alat"
                                    type="button" role="tab" aria-controls="v-pills-alat" aria-selected="false">Fish
                                    Equipment</button>
                                <button class="nav-link bg-tranparent text-body m-2" style="text-align:left"
                                    id="v-pills-ikan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-ikan"
                                    type="button" role="tab" aria-controls="v-pills-ikan"
                                    aria-selected="false">Fish</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- On screens that are 600px or less, set the display none --}}
            <div class="container nav-atas overflow-auto">
                <div class="d-flex nav nav-pills" style="width: 125vw" id="v-pills-tab" role="tablist">
                    <button type="button" class="btn btn-outline-secondary rounded-pill mr-2"><i
                            class='bx bx-menu-alt-left'></i> Filter</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill mr-2" id="v-pills-Semua-tab"
                        data-bs-toggle="pill" data-bs-target="#v-pills-Semua" type="button" role="tab"
                        aria-controls="v-pills-Semua" aria-selected="true">All Product</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill mr-2" id="v-pills-makanan-tab"
                        data-bs-toggle="pill" data-bs-target="#v-pills-makanan" type="button" role="tab"
                        aria-controls="v-pills-makanan" aria-selected="false">Fish Food</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill mr-2" id="v-pills-alat-tab"
                        data-bs-toggle="pill" data-bs-target="#v-pills-alat" type="button" role="tab"
                        aria-controls="v-pills-alat" aria-selected="false">Fish Equipment</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill mr-2" id="v-pills-ikan-tab"
                        data-bs-toggle="pill" data-bs-target="#v-pills-ikan" type="button" role="tab"
                        aria-controls="v-pills-ikan" aria-selected="false">Fish</button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-Semua" role="tabpanel"
                            aria-labelledby="v-pills-Semua-tab">
                            <div class="container mt-3">
                                <h5><b>All Product</b></h5>
                            </div>
                            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <a href="/detail_onelito_store"><img src="img/bio_media.png" alt="bio media"
                                                class="card-img-top" height="170"></a>
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 1.300.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/uniring.jpeg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Uniring rubber hose /
                                                selang aerasi</p>
                                        </div>
                                        <p><b>Rp. 580.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/selang.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 90.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bak_ukur.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Mistar ukur koi /
                                                bak ukur</p>
                                        </div>
                                        <p><b>Rp. 600.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/matala.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>matala Abu Media Filter
                                                Mekanik</p>
                                        </div>
                                        <p><b>Rp. 974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bio_media.png" alt="bio media" class="card-img-top"
                                            height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 1.300.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/uniring.jpeg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Uniring rubber hose /
                                                selang aerasi</p>
                                        </div>
                                        <p><b>Rp. 580.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/selang.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 90.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bak_ukur.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Mistar ukur koi /
                                                bak ukur</p>
                                        </div>
                                        <p><b>Rp. 600.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/matala.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>matala Abu Media Filter
                                                Mekanik</p>
                                        </div>
                                        <p><b>Rp. 974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bio_media.png" alt="bio media" class="card-img-top"
                                            height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 1.300.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/uniring.jpeg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Uniring rubber hose /
                                                selang aerasi</p>
                                        </div>
                                        <p><b>Rp. 580.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/selang.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 90.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bak_ukur.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Mistar ukur koi /
                                                bak ukur</p>
                                        </div>
                                        <p><b>Rp. 600.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/matala.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>matala Abu Media Filter
                                                Mekanik</p>
                                        </div>
                                        <p><b>Rp. 974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bio_media.png" alt="bio media" class="card-img-top"
                                            height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 1.300.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/uniring.jpeg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Uniring rubber hose /
                                                selang aerasi</p>
                                        </div>
                                        <p><b>Rp. 580.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/selang.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 90.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bak_ukur.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Mistar ukur koi /
                                                bak ukur</p>
                                        </div>
                                        <p><b>Rp. 600.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/matala.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>matala Abu Media Filter
                                                Mekanik</p>
                                        </div>
                                        <p><b>Rp. 974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-makanan" role="tabpanel"
                            aria-labelledby="v-pills-makanan-tab">
                            <div class="container mt-3">
                                <h5><b>Fish Food</b></h5>
                            </div>
                            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bio_media.png" alt="bio media" class="card-img-top"
                                            height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 1.300.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/uniring.jpeg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Uniring rubber hose /
                                                selang aerasi</p>
                                        </div>
                                        <p><b>Rp. 580.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/selang.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 90.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bak_ukur.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Mistar ukur koi /
                                                bak ukur</p>
                                        </div>
                                        <p><b>Rp. 600.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/matala.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>matala Abu Media Filter
                                                Mekanik</p>
                                        </div>
                                        <p><b>Rp. 974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-alat" role="tabpanel" aria-labelledby="v-pills-alat-tab">
                            <div class="container mt-3">
                                <h5><b>Fish Equipment</b></h5>
                            </div>
                            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bio_media.png" alt="bio media" class="card-img-top"
                                            height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 1.300.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/uniring.jpeg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Uniring rubber hose /
                                                selang aerasi</p>
                                        </div>
                                        <p><b>Rp. 580.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/selang.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Bio Tube Bacteria House
                                                Media Filter</p>
                                        </div>
                                        <p><b>Rp. 90.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/bak_ukur.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Mistar ukur koi /
                                                bak ukur</p>
                                        </div>
                                        <p><b>Rp. 600.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/matala.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>matala Abu Media Filter
                                                Mekanik</p>
                                        </div>
                                        <p><b>Rp. 974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-ikan" role="tabpanel" aria-labelledby="v-pills-ikan-tab">
                            <div class="container mt-3">
                                <h5><b>Fish</b></h5>
                            </div>
                            <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/koi_3.jpg" alt="bio media" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Jenis ikan | Parent Fish | Pedigree | Size | Farm</p>
                                        </div>
                                        <p><b>Rp. 7.000.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/koi_2.jpg" alt="uniring" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Jenis ikan | Parent Fish | Pedigree | Size | Farm</p>
                                        </div>
                                        <p><b>Rp. 2.500.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/koi_3.jpg" alt="selang" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Jenis ikan | Parent Fish | Pedigree | Size | Farm</p>
                                        </div>
                                        <p><b>Rp. 3.500.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/koi_3.jpg" alt="bak_ukur" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Jenis ikan | Parent Fish | Pedigree | Size | Farm</p>
                                        </div>
                                        <p><b>Rp. 5.000.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-0 border bg-light cart">
                                        <img src="img/koi_2.jpg" alt="matala" class="card-img-top" height="170">
                                        <div class="cb-judul">
                                            <p>Jenis ikan | Parent Fish | Pedigree | Size | Farm</p>
                                        </div>
                                        <p><b>Rp. 3.974.000</b></p>
                                        <div class="col" style="text-align: end">
                                            <button class="border rounded-1 m-2 text-black-50"
                                                style="background-color: transparent;font-size:small"><i
                                                    class="far fa-heart"></i> <span>Wishlist</span></button>
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
@endsection
