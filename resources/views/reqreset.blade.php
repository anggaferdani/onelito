<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.1/dist/flowbite.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <style>
        .select2-container--default .select2-selection--single {
            height: 100%;
            border-radius: 7px;
            border-color: rgb(209 213 219);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 7px 10px;
            color: rgb(17, 24, 39);
            font-size: 14px
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
            right: 10px;
        }

        .relative #form-edit {
            height: 1000rem;
        }
    </style>


    <title>ONELITO KOI</title>
</head>

<body>
    <div class="container-md d-flex flex-column" style="min-height: 100vh;">
        <div class="pt-5 mb-4">
            <a href="/login" class="text-dark d-inline-block" style="text-decoration: blink"><i
                    class="fa-solid fa-arrow-left text dark"></i> Kembali ke halaman utama</a>
        </div>

        <div class="flex-grow-1 d-flex flex-column justify-content-center pb-5">
            <center><img src="img/oneli.svg" alt="ONELITO" class="mb-4"></center>

            <div class="row mb-4">
                <center>
                    <h2 class="mb-2">Lupa Password?</h2>
                    <p class="text-muted mb-0" style="width: 100%; max-width: 400px;">
                        Masukkan email yang terdaftar di akun Onelito Koi Anda. Kami akan mengirimkan
                        link untuk membuat password baru ke email tersebut.
                    </p>
                </center>
            </div>

            <center>
                <div style="width: 100%; max-width: 400px;">
                    @if ($errors->any())
                        <div class="alert alert-danger text-start">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success text-start">
                            {!! session('success') !!} Silakan periksa inbox atau folder spam email Anda.
                        </div>
                    @endif
                </div>
            </center>

            <form method="POST" id="reqreset" action="/reqreset" class="mt-2" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <center>
                            <div style="width: 100%; max-width: 400px;" class="mb-4">
                                <div class="relative">
                                    <input type="email" name="email" id="email" autocomplete="off" required
                                        class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="email"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Email</label>
                                </div>
                            </div>
                            </center>
                        </div>
                    </div>
                </div>

                <center>
                    <div class="d-grid gap-2 mx-auto mb-3" style="width: 100%; max-width: 400px;">
                        <button type="submit"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                            Kirim Link Reset Password</button>
                    </div>
                    <p class="mb-0">Sudah ingat password Anda? <a class="text-danger" style="text-decoration: blink" href="/login">Kembali ke login</a></p>
                </center>
            </form>
        </div>

        <!-- Modal -->

    </div>

    <!-- <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>

</html>
