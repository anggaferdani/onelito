@extends('layout.mainlog')

@section('container')
    <style>
        /* On screens that are 992px or less, set the background color to blue */
        @media screen and (min-width: 601px) {
                .res {
                    display: none
                }
            }

            /* On screens that are 600px or less, set the background color to olive */
            @media screen and (max-width: 600px) {
                .web {
                    display: none;
                }
            }

        .bottom {
            position: absolute;
            margin-top: 18.2%;
            width: 99%;
            
        }

        .cb-judul {
            height: 5rem;
        }
    </style>

    <br><br><br><br>

    <div class="container-fluid p-0 web">
        <div class="row w-100 m-0">
            <div class="col-12">
                <img src="img/event.png" class="w-100" alt="...">
            </div>
            <div class="bottom col-12">
                <div class="row justify-content-center">
                    <div class="col-2">
                        <div class="card">
                            <div class="card-body p-2 text-center">
                                <p class="m-0" style="font-size: small">CURRENT TOTAL BID</p>
                                <h3 class="m-0 text-danger">1.978.007.000</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="card">
                            <div class="card-body p-2 text-center">
                                <p class="m-0" style="font-size: small">CURRENT TOTAL PRIZE</p>
                                <h3 class="m-0 text-danger">978.007.000</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container res">
        <img src="img/event.png" class="w-100" alt="...">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body p-2 text-center">
                        <p class="m-0" >CURRENT TOTAL BID</p>
                        <h3 class="m-0 text-danger">1.978.007.000</h3>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body p-2 text-center">
                        <p class="m-0" >CURRENT TOTAL PRIZE</p>
                        <h3 class="m-0 text-danger">978.007.000</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div>
            {{-- <h5>Event Auction</h5> --}}
        </div>

        {{-- <div class="row mb-5">
            <div class="col-md-3">
                <div class="card">
                    <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farm</h5>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">10</p>
                        <div class="row">
                            <div class="col-6">
                                <p class="m-0">Current bid</p>
                                <p class="m-0" style="color: red">Rp. 7.500.00</p>
                            </div>

                            <div class="col-6">
                                <p class="m-0" style="text-align: end;">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="/bid" class="btn btn-danger w-100 d-flex justify-content-between">BID NOW
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-md-6">
                                <a href="/detail" class="btn btn-secondary w-100 d-flex justify-content-between">DETAIL
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <a href="#" class="btn btn-light w-100 d-flex justify-content-between">VIDEO <span><i
                                        class="fa-solid fa-circle-chevron-right"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farm</h5>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">10</p>
                        <div class="row">
                            <div class="col-6">
                                <p class="m-0">Current bid</p>
                                <p class="m-0" style="color: red">Rp. 7.500.00</p>
                            </div>

                            <div class="col-6">
                                <p class="m-0" style="text-align: end;">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-danger w-100 d-flex justify-content-between">BID NOW
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-secondary w-100 d-flex justify-content-between">DETAIL
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <a href="#" class="btn btn-light w-100 d-flex justify-content-between">VIDEO <span><i
                                        class="fa-solid fa-circle-chevron-right"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farm</h5>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">10</p>
                        <div class="row">
                            <div class="col-6">
                                <p class="m-0">Current bid</p>
                                <p class="m-0" style="color: red">Rp. 7.500.00</p>
                            </div>

                            <div class="col-6">
                                <p class="m-0" style="text-align: end;">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-danger w-100 d-flex justify-content-between">BID NOW
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-secondary w-100 d-flex justify-content-between">DETAIL
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <a href="#" class="btn btn-light w-100 d-flex justify-content-between">VIDEO <span><i
                                        class="fa-solid fa-circle-chevron-right"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Jenis ikan | Parent Fish | Pedigree | Size | Farm</h5>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">10</p>
                        <div class="row">
                            <div class="col-6">
                                <p class="m-0">Current bid</p>
                                <p class="m-0" style="color: red">Rp. 7.500.00</p>
                            </div>

                            <div class="col-6">
                                <p class="m-0" style="text-align: end;">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-danger w-100 d-flex justify-content-between">BID NOW
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-secondary w-100 d-flex justify-content-between">DETAIL
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <a href="#" class="btn btn-light w-100 d-flex justify-content-between">VIDEO <span><i
                                        class="fa-solid fa-circle-chevron-right"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3 mb-5">
            <div class="col mt-3">
                <div class="card">
                    <img src="img/koi11.jpg" class="card-img-top" alt="..." style="height: 310px">
                    <div class="card-body">
                        <div class="cb-judul">
                            <h5 class="card-title">{!! Illuminate\Support\Str::limit("Jenis ikan | Parent Fish | Pedigree | Size | Farm", 55) !!}</h5>
                        </div>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">15</p>
                        <div class="row">
                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                <p class="m-0" style="color: red;font-size:75%">Rp. 7.500.000</p>
                            </div>

                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="text-align: end;font-size: 80%">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;font-size:75%;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 p-0 px-sm-2">
                                <a href="/login" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                    style="font-size: 80%">BID NOW <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-6 col-md-6 pe-0 px-sm-2">
                                <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between px-1 p-1"
                                    style="font-size: 80%">DETAIL <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-9 p-0">
                                <a href="/login" class="btn btn-light w-100 d-flex justify-content-between">VIDEO
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-3 p-0">
                                <button class="border-0 m-1" style="background-color: transparent;font-size:larger"><i
                                        class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card">
                    <img src="img/koi11.jpg" class="card-img-top" alt="..." style="height: 310px">
                    <div class="card-body">
                        <div class="cb-judul">
                            <h5 class="card-title">{!! Illuminate\Support\Str::limit("Jenis ikan | Parent Fish | Pedigree | Size | Farm ksjydgakjwydgajywdgayjw", 55) !!}</h5>
                        </div>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">15</p>
                        <div class="row">
                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                <p class="m-0" style="color: red;font-size:75%">Rp. 7.500.000</p>
                            </div>

                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="text-align: end;font-size: 80%">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;font-size:75%;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 p-0 px-sm-2">
                                <a href="/login" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                    style="font-size: 80%">BID NOW <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-6 col-md-6 pe-0 px-sm-2">
                                <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between px-1 p-1"
                                    style="font-size: 80%">DETAIL <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-9 p-0">
                                <a href="/login" class="btn btn-light w-100 d-flex justify-content-between">VIDEO
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-3 p-0">
                                <button class="border-0 m-1" style="background-color: transparent;font-size:larger"><i
                                        class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card">
                    <img src="img/koi11.jpg" class="card-img-top" alt="..." style="height: 310px">
                    <div class="card-body">
                        <div class="cb-judul">
                            <h5 class="card-title">{!! Illuminate\Support\Str::limit("Jenis ikan | Parent Fish | Pedigree | Size | Farm ksjydgakjwydgajywdgayjw", 55) !!}</h5>
                        </div>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">15</p>
                        <div class="row">
                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                <p class="m-0" style="color: red;font-size:75%">Rp. 7.500.000</p>
                            </div>

                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="text-align: end;font-size: 80%">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;font-size:75%;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 p-0 px-sm-2">
                                <a href="/login" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                    style="font-size: 80%">BID NOW <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-6 col-md-6 pe-0 px-sm-2">
                                <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between px-1 p-1"
                                    style="font-size: 80%">DETAIL <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-9 p-0">
                                <a href="/login" class="btn btn-light w-100 d-flex justify-content-between">VIDEO
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-3 p-0">
                                <button class="border-0 m-1" style="background-color: transparent;font-size:larger"><i
                                        class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card">
                    <img src="img/koi11.jpg" class="card-img-top" alt="..." style="height: 310px">
                    <div class="card-body">
                        <div class="cb-judul">
                            <h5 class="card-title">{!! Illuminate\Support\Str::limit("Jenis ikan | Parent Fish | Pedigree | Size | Farm ksjydgakjwydgajywdgayjw", 55) !!}</h5>
                        </div>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">15</p>
                        <div class="row">
                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                <p class="m-0" style="color: red;font-size:75%">Rp. 7.500.000</p>
                            </div>

                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="text-align: end;font-size: 80%">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;font-size:75%;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 p-0 px-sm-2">
                                <a href="/login" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                    style="font-size: 80%">BID NOW <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-6 col-md-6 pe-0 px-sm-2">
                                <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between px-1 p-1"
                                    style="font-size: 80%">DETAIL <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-9 p-0">
                                <a href="/login" class="btn btn-light w-100 d-flex justify-content-between">VIDEO
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-3 p-0">
                                <button class="border-0 m-1" style="background-color: transparent;font-size:larger"><i
                                        class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card">
                    <img src="img/koi11.jpg" class="card-img-top" alt="..." style="height: 310px">
                    <div class="card-body">
                        <div class="cb-judul">
                            <h5 class="card-title">{!! Illuminate\Support\Str::limit("Jenis ikan | Parent Fish | Pedigree | Size | Farm ksjydgakjwydgajywdgayjw", 55) !!}</h5>
                        </div>
                        <p class="m-0">Number of bids</p>
                        <p class="" style="color: red">15</p>
                        <div class="row">
                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="font-size:80%">Harga saat ini</p>
                                <p class="m-0" style="color: red;font-size:75%">Rp. 7.500.000</p>
                            </div>

                            <div class="col-6 p-0 px-lg-2">
                                <p class="m-0" style="text-align: end;font-size: 80%">Countdown</p>
                                <p class="m-0" style="text-align: end;color :red;font-size:75%;">00:35:45</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 p-0 px-sm-2">
                                <a href="/login" class="btn btn-danger w-100 d-flex justify-content-between p-1"
                                    style="font-size: 80%">BID NOW <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-6 col-md-6 pe-0 px-sm-2">
                                <a href="/login" class="btn btn-secondary w-100 d-flex justify-content-between px-1 p-1"
                                    style="font-size: 80%">DETAIL <span><i
                                            class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-9 p-0">
                                <a href="/login" class="btn btn-light w-100 d-flex justify-content-between">VIDEO
                                    <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                            </div>
                            <div class="col-3 p-0">
                                <button class="border-0 m-1" style="background-color: transparent;font-size:larger"><i
                                        class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br>
@endsection
