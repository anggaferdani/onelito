@extends('profil')

@section('container')
    <div class="container my-3 text-center">
        <h5><i class="fa-solid fa-user"></i> <strong>Profile</strong></h5>
    </div>
    <div class="container p-0">
        <img src="img/foto.png" class="card-img-top px-5" alt="image">
        <div class="container text-center">
            <button type="button" class="btn btn-light btn-sm">
                <a href="#" class="border btn btn-light" style="width: 68vw">
                    Change photo</a>
            </button>
        </div>
        <div class="">
            <div class="mb-3">
                <p class="m-0">Name:</p>
                <p><b>John Doe</b></p>
            </div>
            <div class="mb-3">
                <p class="m-0">Email:</p>
                <p><b>johndoe@gmail.com</b></p>
            </div>
            <div class="mb-3">
                <p class="m-0">Phone number:</p>
                <p><b>0857 5694 2365</b></p>
            </div>
            <div class="mb-3">
                <p class="m-0">Address:</p>
                <p><b>Jl. Tandon Ciater D No. 50, BSD, Ciater, Serpong Sub-District,
                        South Tangerang City, Banten 15310</b></p>
            </div>
        </div>
    </div>
@endsection
