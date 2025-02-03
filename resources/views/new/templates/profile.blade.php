<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        <div class="row g-3 py-5">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-white p-3">
                        <div class="row g-3 align-items-center">
                          <div class="col-2">
                            <img src="{{ $auth->profile_pic ? asset('storage/' . $auth->profile_pic) : asset('/img/default.png') }}" alt="" class="img-fluid rounded-circle">
                          </div>
                          <div class="col-10">
                            <div class="fw-bold fs-5">{{ $auth->nama }}</div>
                            <div>{{ $auth->email }}</div>
                          </div>
                        </div>
                    </div>
                    <div class="card-body">
                      <div class="row g-3 align-items-center">
                        <div class="col-2">
                          <div class="text-center"><i class="fa-solid fa-coins fs-3 text-warning"></i></div>
                        </div>
                        <div class="col-10">
                          <div class="fw-bold">{{ 'Rp. ' . number_format($auth->coin, 0, '.', '.') }} Point</div>
                        </div>
                      </div>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="btn btn-danger w-100 confirmation">Log Out</a>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header bg-white">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('profile.index') ? 'text-danger' : 'text-dark' }}" href="{{ route('profile.index') }}">Data Diri</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('alamat.*') ? 'text-danger' : 'text-dark' }}" href="{{ route('alamat.index') }}">Daftar Alamat</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('profile.card') ? 'text-danger' : 'text-dark' }}" href="{{ route('profile.card') }}">Member Card</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('profile.notifikasi') ? 'text-danger' : 'text-dark' }}" href="{{ route('profile.notifikasi') }}">Notifikasi</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ Route::is('profile.aktivitas-login') ? 'text-danger' : 'text-dark' }}" href="{{ route('profile.aktivitas-login') }}">Aktivitas Login</a>
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