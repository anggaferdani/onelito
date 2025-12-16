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

<div class="">
  <div class="fs-3 fw-bold mb-3">Notifikasi</div>
  <hr class="m-0">

  @foreach($notifications as $notification)
    <div class="card px-0 py-3 border-0 notification-item"
         style="cursor: pointer; {{ $notification->status == 1 ? 'background: RGBA(170, 217, 187, 0.2);' : '' }}"
         data-id="{{ $notification->id }}"
         data-type="{{ $notification->type }}">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <div class="fw-bold">{{ $notification->label }}</div>
        <div class="small text-muted">
          {{ $notification->created_at?->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
        </div>
      </div>
      <div class="text-muted">{{ $notification->description }}</div>
      @if($notification->link)
        <a href="{{ $notification->link }}" class="stretched-link"></a>
      @endif
    </div>

    @if(!$loop->last)
      <hr class="m-0">
    @endif
  @endforeach

  <div class="d-flex justify-content-end mt-3">
    {{ $notifications->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.notification-item').forEach(function(item) {
  item.addEventListener('click', function() {
    let id = this.getAttribute('data-id');
    let type = this.getAttribute('data-type');
    let el = this;

    fetch('{{ route('profile.notifikasi.update') }}', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        notification_id: id,
        type: type
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        el.style.background = 'transparent';
      }
    })
    .catch(err => console.error(err));
  });
});
</script>
@endpush
