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
            <img src="{{ url('img/logo-bawah.png') }}" alt="ONELITO" class="pb-1 pb-lg-1 p-lg-5 py-5 w-75">
        </div>
        <div class="col-4 col-lg-5">
            <nav class="justify-content-center navbar navbar-dark navbar-expand-lg py-5">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/auction">AUCTION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/onelito_store">ONELITO STORE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/koi_stok">KOI STOCK</a>
                    </li>
                    @auth('member')
                        <li class="nav-item">
                            <a class="nav-link" href="/profil"><i
                                    class="fa-solid fa-circle-user" style="font-size: x-large"></i></a>
                        </li>
                    @endauth
                    @guest('member')
                        <li class="nav-item">
                            <a class="nav-link"
                                href="/login">LOGIN</a>
                        </li>
                    @endguest
                </ul>
            </nav>
        </div>
        <div class="col-5 col-lg-4 py-5 row">
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
