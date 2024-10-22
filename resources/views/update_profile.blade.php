@extends('profil')

@section('container')
<div class="container mt-3 my-3">
    <h5><i class="fa-solid fa-key"></i> <b>Update Profile</b></h5>
</div>
<div class="container overflow-hidden p-0">
    <div class="card">
    </div>
    <div class="row p-5">
        <form class="form" method="POST" action="/update-profile" role="form"
            autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="name" name="name" class="form-control"
                    id="name" required="" value="{{ $auth->nama }}">
            </div>
            <div class="form-group mt-3">
                <label for="no_hp">Phone Number</label>
                <input type="no_hp" name="no_hp" class="form-control"
                    id="no_hp" required="" value="{{ $auth->no_hp }}">
            </div>
            <div class="form-group mt-3">
                <label for="alamat">Alamat</label>
                <input type="alamat" name="alamat" class="form-control"
                    id="alamat" required="" value="{{ $auth->alamat }}">
            </div>
            <div class="form-group mt-3">
                <!-- <button type="submit" class="btn btn-success btn-lg float-right">Save</button> -->
                <button type="submit"
                    class="btn btn-danger w-100 justify-content-between"
                    style="background-color:#dc3545" role="button">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection
