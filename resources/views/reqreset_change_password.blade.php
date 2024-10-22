<!doctype html>
<html lang="en">

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
    <div class="container w-75">
        <div style="position: absolute">
            <a href="/login" class="text-dark float-start" style="text-decoration: blink"><i
                    class="fa-solid fa-arrow-left text dark"></i> Back to main page</a>
        </div>
        <center><img src="{{ url('img/oneli.svg') }}" alt="ONELITO" class="my-5 pt-10"></center>

        <div class="row">
            <center>
            <p><h2>Reset password</h2></p>
            <br>
            <p style="color:grey">Masukkan password baru.</p>
            </center>
        </div>
        <br>
        <br>
        <br>
        <form method="POST" id="reqreset" action="/ls/reset?token={{ Request::get('click') }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <center>
                        <div class="col-lg-4 mb-3">
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="password"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Kata
                                    Sandi</label>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="relative">
                                <input type="password" name="confirmpassword" id="confirmpassword" required
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="confirmpassword"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Konfirmasi
                                    Kata Sandi</label>
                            </div>
                        </div>
                        </center>
                    </div>
                </div>
            </div>

            <br>
            <center>
                <div class="d-grid gap-2 col-lg-4 mx-auto">
                    <button type="submit"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-5 py-2.5 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        Simpan </button>
                </div>
            </center>
            <br>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    <ul>
                            <li>{!! session('success') !!}</li>
                    </ul>
                </div>
            @endif
        </form>

        <!-- Modal -->

    </div>
    </div>

    <!-- <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirmpassword");

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
</body>

</html>
