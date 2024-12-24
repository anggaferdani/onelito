@extends('new.templates.profile')
@section('title', 'Notifikasi')
@section('content')
@if(Session::get('success'))
  <div class="alert alert-important alert-success" role="alert">
    {{ Session::get('success') }}
  </div>
@endif
@if(Session::get('error'))
  <div class="alert alert-important alert-danger" role="alert">
    {{ Session::get('error') }}
  </div>
@endif
@php
  $auth = Auth::guard('member')->user();
@endphp
<div class="">
  <div class="fs-3 fw-bold mb-3">Notifikasi</div>
  <hr class="m-0">
  @foreach($notifications as $notification)
    <div class="card px-0 py-3 border-0">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <div class="fw-bold">{{ $notification->label }}</div>
        <div class="small text-muted">{{ $notification->created_at }}</div>
      </div>
      <div class="text-muted">{{ $notification->description }}</div>
      @if($notification->link)<a href="{{ $notification->link }}" class="stretched-link"></a>@endif
    </div>
    <hr class="m-0">
  @endforeach
  <div class="d-flex justify-content-end mt-3">{{ $notifications->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
</div>
@endsection