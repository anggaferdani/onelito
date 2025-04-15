@extends('new.templates.pages')
@section('title', 'Terms & Conditions')
@section('content')
<div class="my-5">
  <div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="{{ route('terms') }}" class="list-group-item list-group-item-action {{ Route::is('terms') ? 'fw-bold' : '' }}">Terms & Conditions</a>
            <a href="{{ route('privacy') }}" class="list-group-item list-group-item-action {{ Route::is('privacy') ? 'fw-bold' : '' }}">Return and Refund Policy</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="fs-1 fw-bold mb-3">{{ $setting->title }}</div>
        <div>{!! $setting->description !!}</div>
    </div>
  </div>
</div>
@endsection