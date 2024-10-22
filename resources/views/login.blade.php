<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.1/dist/flowbite.min.css" />

    <title>ONELITO KOI</title>
</head>

<body style="background-image: url('img/login.png'); background-size: cover">
    <style>
        body {
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        /* On screens that are 992px or less, set the background color to blue */
        @media screen and (min-width: 601px) {
            .res {
                display: none;
            }

            .card.mobile {
                height: 100%;
                height: -webkit-fill-available;
            }
        }

        /* On screens that are 600px or less, set the background color to olive */
        @media screen and (max-width: 600px) {
            .web {
                display: none;
            }
        }
    </style>

    <div class="res">
        <div class="container d-flex justify-content-center align-content-center p-0">
            <div class="card mobile w-100" style="height: 100vh;min-height:750px">
                <div class="card-body">
                    <a href="/" class="text-dark" style="text-decoration: blink"><i
                            class="fa-solid fa-arrow-left text dark"></i> back to main page</a>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div>
                        <center><img src="img/oneli.svg" alt="ONELITO" class="m-5"></center>
                        <br>
                        <form method="POST" action="/authentications">
                            @csrf
                            <div class="relative">
                                <input type="text" name="email" id="email"
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="email"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Email</label>
                            </div>
                            <br>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="passowrd"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Password</label>
                            </div>
                            <br>
                            @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">{{ Session::get('message') }}</h4>
                                    <p>Akun Anda saat ini sedang dalam tahap peninjauan terlebih dahulu.</p>
                                    <hr>
                                    <p class="mb-0">Mohon menunggu email notifikasi selanjutnya apabila proses
                                        registrasi telah berhasil.</p>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <br><br><br>

                            <center class="my-5">
                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button href="" type="submit"
                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        LOGIN </button>
                                </div>
                                <p>Don't have an account yet let's join <a class="text-danger"
                                        style="text-decoration: blink" href="/registrasi">here</a></p>
                                <p>Forget your password? <a class="text-danger" style="text-decoration: blink"
                                        href="/reqreset">Click here</a></p>
                            </center>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="web">
        <div class="container d-flex justify-content-center align-content-center">
            <div class="card" style="width: 45vw; height: 100vh;min-height:750px">
                <div class="card-body">
                    <a href="/" class="text-dark" style="text-decoration: blink"><i
                            class="fa-solid fa-arrow-left text dark"></i> back to main page</a>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div>
                        <center><img src="img/oneli.svg" alt="ONELITO" class="m-5"></center>
                        <br>
                        <form method="POST" action="/authentications">
                            @csrf
                            <div class="relative">
                                <input type="text" name="email" id="email"
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="email"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Email</label>
                            </div>
                            <br>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="passowrd"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Password</label>
                            </div>
                            <br>
                            @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">{{ Session::get('message') }}</h4>
                                </div>
                            @endif

                            @if (Session::has('password'))
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Password berhasil diubah</h4>
                                    <hr>
                                    <br>
                                    <p class="mb-0">Silahkan login menggunakan password baru.</p>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <br><br><br>

                            <center>
                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button herf="/" type="submit"
                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        LOGIN </button>
                                </div>
                                <p>Don't have an account yet let's join <a class="text-danger"
                                        style="text-decoration: blink" href="/registrasi">here</a></p>
                                <p>Forget your password? <a class="text-danger" style="text-decoration: blink"
                                        href="/reqreset">Click here</a></p>
                        </form>
                        </center>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>
