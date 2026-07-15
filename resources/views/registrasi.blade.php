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
        .select2-container {
            width: 100% !important;
        }

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


    <title>Daftar | Onelito Koi</title>
</head>

<body>
    <div class="container-md d-flex flex-column" style="min-height: 100vh;">
        <div class="pt-5 mb-4">
            <a href="/login" class="text-dark d-inline-block" style="text-decoration: blink"><i
                    class="fa-solid fa-arrow-left text dark"></i> Kembali ke halaman utama</a>
        </div>

        <div class="flex-grow-1 d-flex flex-column justify-content-center pb-5">
        <center>
            <img src="img/oneli.svg" alt="ONELITO" class="mb-3">
            <h2 class="mb-1">Daftar Akun</h2>
            <p class="text-muted mb-4">Lengkapi data di bawah untuk membuat akun Onelito Koi.</p>
        </center>

        <form method="POST" id="registration" action="{{ route('register') }}" autocomplete="off">
            @csrf
            @if(request('google_id'))
                <input type="hidden" value="{{ request('google_id') }}" name="google_id">
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-secondary mb-4">
                Pastikan nomor telepon yang Anda masukkan aktif dan terhubung dengan WhatsApp.
                Kode verifikasi 6 digit akan dikirimkan ke nomor tersebut dan berlaku selama 10 menit.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="relative">
                                <input value="{{ old('nama.0', request('firstName')) }}" type="text" name="nama[]" id="namadepan" autocomplete="off" required
                                    class="bg-transparent block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="namadepan"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Nama
                                    depan</label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="relative">
                                <input value="{{ old('nama.1', request('lastName')) }}" type="text" name="nama[]" id="namabelakang" autocomplete="off" required
                                    class="bg-transparent block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="namabelakang"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Nama
                                    belakang</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="relative">
                                <input value="{{ old('email', request('email')) }}" type="email" name="email" id="email" autocomplete="off" @if(request('google_id')) readonly @endif required
                                    class="@if(request('google_id')) border-danger @endif bg-transparent block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="email"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Email</label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="relative mb-2">
                                <input type="password" name="password" id="password" autocomplete="new-password" required
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="password"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Kata
                                    Sandi</label>
                            </div>
                            <div class="text-muted small">Password minimal 8 karakter, gunakan kombinasi huruf dan angka</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="relative mb-2">
                                <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="new-password" required
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="confirmpassword"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Konfirmasi
                                    Kata Sandi</label>
                            </div>
                            <div class="text-muted small">Konfirmasi password harus sama dengan password</div>
                        </div>
                        <div class="mb-3">
                            <div class="relative mb-2">
                                <input value="{{ old('no_hp') }}" type="text" inputmode="numeric" pattern="[0-9]*" name="no_hp" id="no_hp" autocomplete="off" required
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="no_hp"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">No. Handphone</label>
                            </div>
                            <div class="text-muted small">Pastikan nomor yang dimasukkan aktif dan terhubung dengan WhatsApp</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="relative ">
                                <select name="provinsi" id="provinsi" required class="select2-setup form-control">
                                    <option value="" {{ old('provinsi') ? '' : 'selected' }}></option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->prov_id }}" {{ old('provinsi') == $province->prov_id ? 'selected' : '' }}>
                                            {{ $province->prov_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="relative">
                                <select name="kota" id="kota" required
                                    class="select2-setup form-control js-data-example-ajax">
                                    @if(old('kota') && $oldKota)
                                        <option value="{{ old('kota') }}" selected>{{ $oldKota->city_name }}</option>
                                    @else
                                        <option></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="relative">
                                <select name="kecamatan" id="kecamatan" required class="select2-setup form-control">
                                    @if(old('kecamatan') && $oldKecamatan)
                                        <option value="{{ old('kecamatan') }}" selected>{{ $oldKecamatan->dis_name }}</option>
                                    @else
                                        <option></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="relative">
                                <select id="kelurahan" name="kelurahan" required class="select2-setup form-control">
                                    @if(old('kelurahan') && $oldKelurahan)
                                        <option value="{{ old('kelurahan') }}" selected>{{ $oldKelurahan->subdis_name }}</option>
                                    @else
                                        <option></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="relative">
                                <input value="{{ old('alamat') }}" type="text" required name="alamat" id="alamat" autocomplete="off"
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="alamat"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Alamat</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="relative">
                                <input value="{{ old('kode_pos') }}" type="text" name="kode_pos" id="kode_pos" autocomplete="off"
                                    class="block px-2.5 pb-1.5 pt-3 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="kode_pos"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">Kode
                                    pos</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <center class="mt-3">
                <div class="d-grid gap-2 col-lg-4 mx-auto px-lg-4 mb-3">
                    <button type="submit"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        DAFTAR </button>
                </div>
                <p class="mb-5">Sudah punya akun? Langsung masuk <a class="text-danger"
                        style="text-decoration: blink" href="/login">di sini</a></p>
            </center>
        </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body py-4">
                        <center><img src="{{ url('img/frame_reg.png') }}" alt="ceklis">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-3">
                                Registrasi Berhasil
                            </h3>
                            <p class="mb-4">Akun Anda telah dibuat. Kode verifikasi telah dikirim
                                melalui WhatsApp ke nomor yang Anda daftarkan.</p>
                            <a href="/login"><button type="button"
                                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">OKE</button></a>
                        </center>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script type="text/javascript">
      $(document).ready( function () {
        $('form').on('submit', function() {
          $.LoadingOverlay("show");
      
          setTimeout(function(){
              $.LoadingOverlay("hide");
          }, 100000);
        });
      });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#no_hp').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });

            $('#provinsi').select2({
                width: '100%',
                placeholder: 'Provinsi'
            });

            $('#kota').select2({
                width: '100%',
                placeholder: 'Kota'
            });

            $('#kecamatan').select2({
                width: '100%',
                placeholder: 'Kecamatan'
            });

            $('#kelurahan').select2({
                width: '100%',
                placeholder: 'Kelurahan'
            });

            function ajaxChained(source, target, slug) {
                $(source).on('change', function() {
                    var pid = $(source + ' option:selected').val(); //$(this).val();
                    $.ajax({
                        type: 'GET',
                        url: '/' + slug + pid,
                        dataType: 'html',
                        success: function(txt) {
                            //no action on success, its done in the next part
                        }
                    }).done(function(data) {
                        //get JSON
                        data = $.parseJSON(data);

                        var placeholderText = 'Kota';

                        //generate <options from JSON, dimulai dengan opsi kosong agar tidak auto-terpilih
                        var list_html = '<option value=""></option>';
                        $.each(data, function(i, item) {
                            if (target === '#kota') {
                                placeholderText = 'Kota';
                                list_html += '<option value=' + data[i].city_id + '>' +
                                    data[i].city_name + '</option>'
                            } else if (target === '#kecamatan') {
                                placeholderText = 'Kecamatan';
                                list_html += '<option value=' + data[i].dis_id + '>' + data[
                                    i].dis_name + '</option>';
                            } else {
                                placeholderText = 'Kelurahan';
                                list_html += '<option value=' + data[i].subdis_id + '>' +
                                    data[i].subdis_name + '</option>';
                            }

                        });
                        //replace <select2 with new options
                        $(target).html(list_html);
                        if (target === '#kota' || target === '#edit_kota') {
                            $('#kecamatan').html('<option value=""></option>');
                            $('#kelurahan').html('<option value=""></option>');
                            $('#kecamatan').select2({
                                width: '100%',
                                placeholder: 'Kecamatan'
                            });
                            $('#kelurahan').select2({
                                width: '100%',
                                placeholder: 'Kelurahan'
                            });

                        } else if (target === '#kecamatan' || target === '#edit_kecamatan') {
                            $('#kelurahan').html('<option value=""></option>');
                            $('#kelurahan').select2({
                                width: '100%',
                                placeholder: 'Kelurahan'
                            });
                        } else {}
                        //pastikan placeholder tetap sesuai nama field, bukan auto-terpilih
                        $(target).select2({
                            width: '100%',
                            placeholder: placeholderText
                        });
                    });
                })
            }

            ajaxChained('#provinsi', '#kota', 'cities?prov_id=');
            ajaxChained('#kota', '#kecamatan', 'districts?city_id=');
            ajaxChained('#kecamatan', '#kelurahan', 'subdistricts?dis_id=');
        });

        //Form Validation
        var password = document.getElementById("password"),
            confirm_password = document.getElementById("confirmpassword");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;

        @if(session('success'))
            $(document).ready(function(){
                $('#exampleModal').modal('show');
            });
        @endif
    </script>
</body>

</html>