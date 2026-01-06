<style>
    @media screen and (max-width: 600px) {
        .font-footer {
            font-size:
        }

        a {
            font-size: 10px !important;
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

<div class="container-fluid" style="background-color: black;
 height = 31hv">
    <div class="row">
        <div class="col-3 col-lg-3 py-4 text-center">
            <img src="{{ url('img/logo-bawah.webp') }}" alt="ONELITO" class="pb-1 pb-lg-1 p-lg-5 py-5 w-75">
        </div>
        <div class="col-4 col-lg-5">
            <nav class="justify-content-center navbar navbar-dark navbar-expand-lg py-5">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'home' ? 'active text-danger' : '' }} " href="/">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'auction' ? 'active text-danger' : '' }} "
                            href="/auction">AUCTION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'onelito_store' ? 'active text-danger' : '' }} "
                            href="/onelito_store">ONELITO STORE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'koi_stok' ? 'active text-danger' : '' }} "
                            href="/koi_stok">KOI STOCK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'login' ? 'active text-danger' : '' }} "
                            href="/login">LOGIN</a>
                    </li>
                </ul>
            </nav>
        </div>
        {{-- <div class="col-5 col-lg-3 justify-content-center mx-auto py-5 row">
            <nav class="navbar-dark p-0">
                <ul class="list-unstyled mr-auto">
                    <li class="nav-item">
                        <p class="nav-link active text-danger m-0" aria-current="page">SUBSCRIBE US</p>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page"
                            href="https://www.instagram.com/onelitokoi.id/?hl=id"><i class="fa-brands fa-instagram"></i>
                            @Onelitokoi.id</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="#"><i
                                class="fa-brands fa-facebook"></i> @Onelito koi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page"
                            href="https://www.youtube.com/channel/UCbhkQaiMUPUVQWw5KBLT0Bw"><i
                                class="fa-brands fa-youtube"></i> Onelito koi</a>
                    </li>
                </ul>
            </nav>
        </div> --}}
        <div class="col-5 col-lg-4 py-5 row">
            {{-- <nav class="navbar-dark p-0">
                <ul class="list-unstyled mr-auto">
                    <li class="nav-item">
                        <p class="active  nav-link text-danger m-0" aria-current="page">SUBSCRIBE US</p>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page"
                            href="https://www.instagram.com/onelitokoi.id/?hl=id">
                            <i class="fa-brands fa-instagram"></i>
                            @onelitokoi.id</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="#"><i
                                class="fa-brands fa-facebook"></i> @Onelito koi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page"
                            href="https://www.youtube.com/channel/UCbhkQaiMUPUVQWw5KBLT0Bw"><i
                                class="fa-brands fa-youtube"></i> Onelito koi</a>
                    </li>
                </ul>
            </nav> --}}
            <div class="col-lg-6 p-0">
                <div class="">
                    <p class="active  nav-link text-danger m-0" aria-current="page">SUBSCRIBE US</p>
                </div>
                <div class="">
                    <a class="nav-link active text-white" aria-current="page"
                        href="https://www.instagram.com/onelitokoi.id/?hl=id" target="_blank">
                        <i class="fa-brands fa-instagram"></i>
                        @onelitokoi.id</a>
                </div>
                <div class="">
                    <a class="nav-link active text-white" aria-current="page" href="https://www.facebook.com/profile.php?id=100089107055034&_rdc=1&_rdr" target="_blank"><i
                            class="fa-brands fa-facebook"></i> @Onelito koi</a>
                </div>
                <div class="">
                    <a class="nav-link active text-white" aria-current="page"
                        href="https://www.youtube.com/channel/UCbhkQaiMUPUVQWw5KBLT0Bw" target="_blank"><i
                            class="fa-brands fa-youtube"></i> Onelito koi</a>
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="">
                    <p class="active  nav-link text-danger m-0" aria-current="page">ONLINE SHOP</p>
                </div>
                <div class="">
                    <a class="nav-link active text-white" aria-current="page"
                        href="https://www.tokopedia.com/onelitokoi?source=universe&st=product" target="_blank"><i class="fa-solid fa-bag-shopping"></i> Tokopedia</a>
                </div>
                <div class="mt-5">
                    <div><a href="{{ route('terms') }}" class="text-white text-decoration-underline">Terms & Conditions</a></div>
                    <div><a href="{{ route('privacy') }}" class="text-white text-decoration-underline">Return and Refund Policy</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
