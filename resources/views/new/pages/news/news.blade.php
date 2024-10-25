@extends('new.templates.pages')
@section('title', 'News')
@section('content')
<div class="fw-bold mb-3 fs-5 mt-3">News Update</div>
<div class="row">
  @foreach($news as $new)
    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <div class="card-img-top" style="width: 100%; height: 200px; background-repeat: no-repeat; background-position: center; background-size: cover; background-image: url('{{ asset('storage/news/' . $new->image) }}');"></div>
        <div class="card-body d-flex flex-column justify-content-between">
          <div>
            <div class="fw-bold lh-sm mb-1">{{ $new->title }}</div>
            <div class="mb-2 text-truncate text-muted">{{ $new->description }}</div>
          </div>
          <div class="text-danger fw-bold small">{{ \Carbon\Carbon::parse($new->created_at)->translatedFormat('l, d M Y') }}</div>
        </div>
        <a href="{{ route('news.detail', ['slug' => $new->slug]) }}" class="stretched-link"></a>
      </div>
    </div>
  @endforeach
  <div class="d-flex justify-content-center">{{ $news->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
</div>
@endsection