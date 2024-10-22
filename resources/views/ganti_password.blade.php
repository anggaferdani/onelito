@extends('profil')

@section('container')
<div class="container mt-3 my-3">
    <h5><i class="fa-solid fa-key"></i> <b>Change Password</b></h5>
</div>
<div class="container overflow-hidden p-0">
    <div class="card">
    </div>
    <div class="row p-5">
        <form class="form" method="POST" action="/change-password" role="form"
            autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" class="form-control"
                    id="password" required="">
            </div>
            <div class="form-group mt-3">
                <label for="password_confirm">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                    id="password_confirmation" required="">
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
