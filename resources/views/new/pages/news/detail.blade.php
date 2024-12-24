@extends('new.templates.pages')
@section('title', 'News')
@section('content')
<div class="row my-4">
  <div class="col-md-6 m-auto">
    <div class="fw-bold mb-3"><a href="{{ route('news') }}" class="text-dark text-decoration-none"><i class="fas fa-arrow-left fs-4"></i></a></div>
    <div class="mb-3" style="width: 100%; height: 300px; background-repeat: no-repeat; background-position: center; background-size: cover; background-image: url('{{ asset('storage/news/' . $new->image) }}');"></div>
    <div class="text-danger mb-2">{{ \Carbon\Carbon::parse($new->created_at)->translatedFormat('l, d M Y') }}</div>
    <div class="fw-bold lh-sm mb-3 fs-4">{{ $new->title }}</div>
    <div class="text-muted mb-3">{!! $new->description !!}</div>
    <div>
      @foreach($new->tags as $tag)
        <span class="badge bg-primary fs-6 rounded-pill">{{ $tag->tag }}</span>
      @endforeach
    </div>
  </div>
</div>
@endsection