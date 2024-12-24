@extends('new.templates.profile')
@section('title', 'Aktivitas Login')
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
  $currentUserAgent = request()->header('User-Agent');
  $currentIpAddress = request()->ip();
  $currentSessionId = session()->getId();
@endphp
<div class="">
  <div class="fs-3 fw-bold">Aktivitas Login</div>
  <div class="mb-3">Bila terdapat aktivitas tidak dikenal, segera klik "Keluar" dan ubah kata sandi.</div>
  <div class="mb-3 text-danger">Aktivitas login saat ini</div>
  <div>
    @foreach($loginHistories as $loginHistory)
      @php
        $isMobile = strpos($loginHistory->user_agent, 'Mobile') !== false;
        $isSameDevice = $loginHistory->user_agent === $currentUserAgent && $loginHistory->ip_address === $currentIpAddress;
        $isCurrentSession = $loginHistory->session_id === $currentSessionId;
      @endphp
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
          @if($isMobile)
            <div class="col-2 text-center"><i class="fa-solid fa-mobile-screen-button fs-1"></i></div>
          @else
            <div class="col-2 text-center"><i class="fa-solid fa-laptop fs-1"></i></div>
          @endif
          <div>
            <div class="fw-bold lh-sm mb-1">{{ $loginHistory->user_agent }}</div>
            <div>{{ $loginHistory->ip_address }}</div>
            @if($isSameDevice)
              <div class="badge bg-danger mb-1">Sedang Aktif</div>
            @else
              <div class="text-muted small">{{ \Carbon\Carbon::parse($loginHistory->created_at)->format('j M Y H:i') }}</div>
            @endif
          </div>
        </div>
        <div class="col-2 text-center">
          @if (!$isCurrentSession)
            <a href="{{ route('logout.device', ['session_id' => $loginHistory->session_id]) }}" class="text-danger text-decoration-none confirmation">Keluar</a>
          @endif
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection