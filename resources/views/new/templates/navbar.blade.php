<style>
  @media screen and (min-width: 601px) {
      .samping {
          display: none;
      }
  }

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

  .sidebar {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: rgb(255, 255, 255);
      overflow-x: hidden;
      padding-top: 15px;
      transition: 0.5s;
  }

  .sidebar a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 15px;
      color: #000;
      display: block;
      transition: 0.3s;
  }

  .sidebar a:hover {
      color: #000000;
  }

  .sidebar .closebtn {
      position: absolute;
      top: 17px;
      right: 17px;
      font-size: 36px;
      margin-left: 50px;
  }

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

  #main {
      transition: margin-left .5s;
      padding: 20px;
  }

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
@php
  $title = null;
  $auth = Auth::guard('member')->user();
@endphp
<div class="atas fix">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
          <a class="w-25 navbar-brand" href="/">
              <img src="{{ url('img/logo-onelito.png') }}" alt="ONELITO" class="w-100">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse flex-grow-0 navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small" href="/">HOME</a>
                  </li>
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small" href="/auction">AUCTION</a>
                  </li>
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small" href="/onelito_store">ONELITO STORE</a>
                  </li>
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small" href="/koi_stok">KOI STOCK</a>
                  </li>
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small" href="/wishlistlog">WISHLIST</a>
                  </li>
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small {{ Route::is('winning-auction') ? 'text-danger' : 'text-dark' }}" href="{{ route('winning-auction') }}">WINNING AUCTION</a>
                  </li>
                  <li class="nav-item">
                    <a style="font-size: 13px;" class="nav-link small {{ Route::is('cart') ? 'text-danger' : 'text-dark' }}" href="{{ route('cart') }}">CART</a>
                </li>
                  <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small {{ Route::is('shopping-cart.*') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.semua') }}">STATUS ORDER</a>
                  </li>
                  {{-- <li class="nav-item">
                      <a style="font-size: 13px;" class="nav-link small {{ Route::is('news') ? 'text-danger' : 'text-dark' }}" href="{{ route('news') }}">NEWS</a>
                  </li> --}}
                  @if($auth)
                  @include('new.notification')
                  <li class="nav-item">
                    <a style="font-size: 13px;" class="nav-link small" href="/profil">
                        <img src="{{ $auth->profile_pic ? asset('storage/' . $auth->profile_pic) : asset('/img/default.png') }}" style="width:24px;height:24px;border-radius:50%;max-width:unset">
                    </a>
                  </li>
                  @else
                  <li class="nav-item">
                    <a style="font-size: 13px;" class="nav-link small {{ $title === 'login' ? 'active text-danger' : '' }}"href="/login">LOGIN</a>
                  </li>
                  @endif
              </ul>
          </div>
      </div>
  </nav>
</div>

<div class="samping fix">
  <div id="mySidebar" class="sidebar">
      <div class="d-flex">
          <a class="navbar-brand" href="/">
              <img src="{{ url('img/logo-onelito.png') }}" alt="ONELITO" class="w-75">
          </a>
          <h2 href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</h2>
      </div>
      <hr>
      <a class="nav-link" href="/">HOME</a>
      <a class="nav-link" href="/auction">AUCTION</a>
      <a class="nav-link" href="/onelito_store">ONELITO STORE</a>
      <a class="nav-link" href="/koi_stok">KOI STOCK</a>

      <a class="nav-link" href="/wishlistlog">WISHLIST</a>
      <a class="nav-link" href="/shoppingcart">WINNING AUCTION</a>
      <a class="nav-link {{ Route::is('shopping-cart.*') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.semua') }}">STATUS ORDER</a>
      <a class="nav-link {{ Route::is('cart') ? 'text-danger' : 'text-dark' }}" href="{{ route('cart') }}">CART</a>
      {{-- <a class="nav-link {{ Route::is('news') ? 'text-danger' : 'text-dark' }}" href="{{ route('news') }}">NEWS</a> --}}


      @if($auth)
      <div class="px-4" style="position: absolute;
      padding-right: 1.5rem!important;
      padding-left: 1.5rem!important;
      width: 100%;
      bottom: 2.5rem;">
          <a style="margin-top: 1rem" class="btn btn-danger fs-6 text-center text-white" href="/logout"
              role="button" style="font-size: x-large">
              <span style="margin-left: -2rem;">Log Out</span>
          </a>
      </div>
      @else
      <a class="nav-link {{ $title === 'login' ? 'active text-danger' : '' }}"href="/login">LOGIN</a>
      @endif
  </div>

  <div id="main" class="d-flex border-bottom" style="background: white">
      <button class="openbtn" onclick="openNav()">&#9776;</button>
      <h2 class="title my-0 mx-auto" style="text-transform: capitalize"></h2>

      @if($auth)
      <a class="nav-link" href="/profil"><img src="{{ $auth->profile_pic ? asset('storage/' . $auth->profile_pic) : asset('/img/default.png') }}" style="width:24px;height:24px;border-radius:50%;max-width:unset"></a>
      @endif
  </div>
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
</nav>
