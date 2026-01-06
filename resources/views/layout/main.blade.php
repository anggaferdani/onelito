<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HJLT8TZL6Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-HJLT8TZL6Y');
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @media screen and (max-width: 600px) {
            .font-footer {
                font-size:
            }
            h3 {
                font-size: 15px !important;
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

        i.fas.fa-heart.wishlist:before {
            color: red;
        }
    </style>

    @stack('styles')

    <title>ONELITO KOI</title>
</head>

<body>
    @auth('member')

        @include('part.navbarlog')

        <div class="">
            @yield('container')
        </div>

        @include('part.footerlog')
    @endauth

    @guest('member')
        @include('part.navbar')
        <div class="">
            @yield('container')
        </div>
        @include('part.footer')
    @endguest

    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
