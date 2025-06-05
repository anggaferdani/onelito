<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.1/dist/flowbite.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

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
    <center><img src="{{ asset('img/oneli.svg') }}" alt="ONELITO" class="my-5 pt-10"></center>

    <div class="row">
        <center>
            <div class="mb-3"><h2>Konfirmasi Nomor Telepon</h2></div>
            <div class="" style="color:grey">
                Silakan periksa kembali nomor telepon Anda.  Jika sudah benar, klik "Submit" untuk melanjutkan.
                Jika ingin mengubah, masukkan nomor yang baru.
            </div>
        </center>
    </div>
    <br>
    <br>
    <br>
    <form method="POST" action="{{ route('post-confirm-phone-number') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $member->email }}">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <center>
                        <div class="col-12 col-md-4 mb-3">
                            <div class="relative">
                                <input type="text" name="no_hp" id="no_hp" required
                                       class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder=" " value="{{ $member->no_hp }}"/>
                                <label for="no_hp"
                                       class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">No. Handphone</label>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
        </div>

        <br>
        <center>
            <div class="d-grid gap-2 col-lg-4 mx-auto">
                <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-5 py-2.5 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                    Submit
                </button>
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

            @if ($errors->has('message'))
                <div class="alert alert-danger">
                    {{ $errors->first('message') }}
                </div>
            @endif
    </form>
</div>
</div>
</body>
</html>