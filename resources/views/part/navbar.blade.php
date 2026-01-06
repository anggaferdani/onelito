<style>
    /* On screens that are 992px or less, set the background color to blue */
    @media screen and (min-width: 601px) {
        .samping {
            display: none;
            z-index:1031;
        }
    }

    /* On screens that are 600px or less, set the background color to olive */
    @media screen and (max-width: 600px) {
        .atas {
            display: none;
        }
    }

    @media screen and (min-width: 601px) and (max-width: 1332px) {
        .nav-link {
            font-size: smaller;
        }
    }

    /* The sidebar menu */
    .sidebar {
        height: 100%;
        /* 100% Full-height */
        width: 0;
        /* 0 width - change this with JavaScript */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Stay on top */
        top: 0;
        left: 0;
        background-color: rgb(255, 255, 255);
        /* Black*/
        overflow-x: hidden;
        /* Disable horizontal scroll */
        padding-top: 15px;
        /* Place content 60px from the top */
        transition: 0.5s;
        /* 0.5 second transition effect to slide in the sidebar */
    }

    /* The sidebar links */
    .sidebar a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 15px;
        color: #000;
        display: block;
        transition: 0.3s;
    }

    /* When you mouse over the navigation links, change their color */
    .sidebar a:hover {
        color: #000000;
    }

    /* Position and style the close button (top right corner) */
    .sidebar .closebtn {
        position: absolute;
        top: 26px;
        right: 16px;
        font-size: 50px;
        margin-left: 50px;
    }

    /* The button used to open the sidebar */
    .openbtn {
        font-size: 20px;
        cursor: pointer;
        background-color: rgb(255, 255, 255);
        color: black;
        padding: 10px 15px;
        border: none;
    }

    .title {
        font-size: 20px;
        cursor: pointer;
        background-color: rgb(255, 255, 255);
        color: black;
        padding: 10px 15px;
        border: none;
    }

    .openbtn:hover {
        background-color: rgb(255, 250, 250);
    }

    /* Style page content - use this if you want to push the page content to the right when you open the side navigation */
    #main {
        transition: margin-left .5s;
        /* If you want a transition effect */
        padding: 20px;
    }

    /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
    @media screen and (max-height: 450px) {
        .sidebar {
            padding-top: 15px;
        }

        .sidebar a {
            font-size: 18px;
        }
    }

    .fix {
        position: fixed;
        z-index:1031;
        width: 100vw;
    }
</style>

<div class="atas fix">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="w-25 navbar-brand" href="/">
                <img src="{{ url('img/logo-onelito.webp') }}" alt="ONELITO" class="w-100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse flex-grow-0 navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link small {{ $title === 'home' ? 'active text-danger' : '' }}"href="/">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small {{ $title === 'auction' ? 'active text-danger' : '' }}"href="/auction">AUCTION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small {{ $title === 'ONELITO STORE' ? 'active text-danger' : '' }}"href="/onelito_store">ONELITO STORE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small {{ $title === 'koi_stok' ? 'active text-danger' : '' }}"href="/koi_stok">KOI STORE</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link small"href="/login">WISHLIST</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small"href="/login">WINNING AUCTION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small"href="/login">CART</a>
                    </li> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link small" href="{{ route('news') }}">NEWS</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link small {{ $title === 'login' ? 'active text-danger' : '' }}"href="/login">LOGIN</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="samping fix">
    <div id="mySidebar" class="sidebar">
        <div class="d-flex">
            <a class="navbar-brand" href="/">
                <img src="{{ url('img/logo-onelito.webp') }}" alt="ONELITO" class="w-75">
            </a>
            <h1 href="javascript:void(0)" class="closebtn" style="font-size: x-large" onclick="closeNav()">&times;</h1>
        </div>
        <hr>
        <a class="nav-link {{ $title === 'home' ? 'active text-danger' : '' }}"href="/">HOME</a>
        <a class="nav-link {{ $title === 'auction' ? 'active text-danger' : '' }}"href="/auction">AUCTION</a>
        <a class="nav-link {{ $title === 'onelito_store' ? 'active text-danger' : '' }}"href="/onelito_store">ONELITO STORE</a>
        <a class="nav-link {{ $title === 'KOI STOCK' ? 'active text-danger' : '' }}"href="/koi_stok">KOI STORE</a>
        {{-- <a class="nav-link"href="/login">WISHLIST</a>
        <a class="nav-link"href="/login">WINNING AUCTION</a>
        <a class="nav-link"href="/login">CART</a> --}}
        {{-- <a class="nav-link" href="{{ route('news') }}">NEWS</a> --}}
        <a class="nav-link {{ $title === 'login' ? 'active text-danger' : '' }}"href="/login">LOGIN</a>
    </div>

    <div id="main" class="d-flex" style="background: white">
        <button class="openbtn" onclick="openNav()">&#9776;</button>
        <h2 class="title my-0 mx-auto" style="text-transform: capitalize">{{ $title }}</h2>
    </div>
    {{-- <aside class="main-navbar elevation-4">
      <div class="sidebar">
        <nav>
          <ul class="nav nav-pills nav-sidebar flex-column">
            <li class="nav-item">
              <a class="nav-link {{ ($title === 'home') ? 'active text-danger' : '' }}"href="/">HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($title === 'auction') ? 'active text-danger' : '' }}"href="/auction">AUCTION</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($title === 'onelito_store') ? 'active text-danger' : '' }}"href="/onelito_store">ONELITO STORE</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($title === 'KOI STOCK') ? 'active text-danger' : '' }}"href="/koi_stok">KOI STORE</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($title === 'login') ? 'active text-danger' : '' }}"href="/login">LOGIN</a>
            </li>
          </ul>
        </nav>
      </div>
    </aside> --}}
</div>
<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }
</script>
