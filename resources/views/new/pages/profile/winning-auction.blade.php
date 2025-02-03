@extends('new.templates.pages')
@section('title', 'Winning Auction')
@section('content')
<div class="my-3">
  @foreach($winners as $winner)
    @php
        if ($winner->photo_path !== null) {
            $cartPhoto = url('storage') . '/' . $winner->photo_path;
        }
    @endphp
    <div class="row g-2">
      <div class="col-3 col-md-2"><img src="{{ $cartPhoto }}" alt="" class="img-fluid border p-2 rounded"></div>
      <div class="col-9 col-md-10">
        <div>
          <div class="m-0 fw-bold mb-2">{{ $winner->no_ikan }} | {{ $winner->variety }} | {{ $winner->breeder }} | {{ $winner->bloodline }} | {{ $winner->size }} | {{ $winner->dob }}</div>
          <div class="m-0"><span class="fw-bold">Note :</span> {!! $winner->note !!}</div>
          <div class="m-0"><span class="fw-bold">Total Bid :</span> Rp. {{ number_format($winner->price, 0, '.', '.') }}</div>
          <div class="m-0"><span class="fw-bold">Tanggal :</span> {{ \Carbon\Carbon::parse($winner->tgl_mulai)->format('d M Y H:i') }} - {{ \Carbon\Carbon::parse($winner->tgl_akhir)->format('d M Y H:i') }}</div>
        </div>
      </div>
    </div>
    <hr>
  @endforeach

  <div class="d-flex justify-content-center my-4">
    {{ $winners->links('pagination::bootstrap-4') }}
  </div>
</div>
@endsection