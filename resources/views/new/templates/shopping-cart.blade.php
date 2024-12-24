<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="biteship-api-key" content="{{ env('BITESHIP_API_KEY') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>@yield('title')</title>
    <style>
      ::-webkit-resizer{
        display: none;
      }
    </style>
</head>
<body>
    @php
      $auth = Auth::guard('member')->user();
    @endphp
    @include('new.templates.navbar')
    <br><br><br><br>
    <div class="container">
        <div class="row py-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-white">
                  <ul class="nav">
                    @php
                      use App\Models\Order;
                      $auth = Auth::guard('member')->user();

                      $belumDibayar = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'pending')->where('status_aktif', 1)->count();
                      $menungguKonfirmasi = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'paid')->where('status_aktif', 1)->count();
                      $sedangDiproses = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'process')->where('status_aktif', 1)->count();
                      $dikirim = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'delivered')->where('status_aktif', 1)->count();
                    @endphp
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.semua') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.semua') }}">Semua</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.belum-dibayar') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.belum-dibayar') }}">Belum Dibayar <span class="text-danger">({{ $belumDibayar }})</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.menunggu-konfirmasi') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.menunggu-konfirmasi') }}">Menunggu Konfirmasi <span class="text-danger">({{ $menungguKonfirmasi }})</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.sedang-diproses') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.sedang-diproses') }}">Sedang Diproses <span class="text-danger">({{ $sedangDiproses }})</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.dikirim') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.dikirim') }}">Dikirim <span class="text-danger">({{ $dikirim }})</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.selesai') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.selesai') }}">Selesai</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('shopping-cart.dibatalkan') ? 'text-danger' : 'text-dark' }}" href="{{ route('shopping-cart.dibatalkan') }}">Dibatalkan</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body p-4">
                  @yield('content')
                </div>
              </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/compressorjs/dist/compressor.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    <script type="text/javascript">
      $('.delete').click(function(){
        Swal.fire({
          title: "Are you sure?",
          text: "Are you sure you want to delete this?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, delete it",
          closeOnConfirm: false
        }).then((result) => {
          if(result.isConfirmed){
            $(this).closest("form").submit();
          }
        });
      });

      $('.confirmation').click(function(event){
        event.preventDefault();
        var deleteUrl = $(this).attr('href');
        Swal.fire({
          title: "Are you sure?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes",
          closeOnConfirm: false
        }).then((result) => {
          if(result.isConfirmed){
              window.location.href = deleteUrl;
          }
        });
      });
    </script>
</body>