@php
  $auth = Auth::guard('member')->user();
  $notifications = App\Models\Notification::where(function($query) use ($auth) {
    $query->whereNull('peserta_id')
          ->orWhere('peserta_id', $auth->id_peserta);
  })
  ->orderBy('created_at', 'desc')
  ->get();
  $unreadCount = $notifications->take(10)->filter(function($notification) {
    return $notification->status == 1;
  })->count();
@endphp
@if($auth)
<li class="nav-item">
  <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
    <i class="fa-solid fa-bell fs-5"></i>
    @if($unreadCount > 0)
      <span class="badge bg-danger rounded-circle border border-white" style="font-size: 10px; transform: translate(-7px, -8px)" id="count">{{ $unreadCount }}</span>
    @endif
  </a>
  <div class="dropdown">
    <ul class="dropdown-menu dropdown-menu-end py-0" style="width: 400px !important; height: 500px; overflow-y: auto;">
        <li class="p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div class="fw-bold fs-5">Notification</div>
            <div><a href="{{ route('profile.notifikasi') }}" class="text-dark text-decoration-none">Lainnya</a></div>
          </div>
        </li>
        <hr class="m-0">
        @foreach($notifications->take(10) as $notification)
          <li class="p-3 notification" @if($notification->status == 1 || $notification->peserta_id == null) style="background: RGBA(170, 217, 187, 0.2);" @endif data-id="{{ $notification->id }}">
            <div class="card bg-transparent p-0 border-0">
              <div class="fw-bold mb-1">{{ $notification->label }}</div>
              <div class="small text-muted">{{ $notification->description }}</div>
              @if($notification->link)<a href="{{ $notification->link }}" class="stretched-link"></a>@endif
            </div>
          </li>
          <hr class="m-0">
        @endforeach
      </ul>
  </div>
</li>
@endif
@push('scripts')
<script>
  document.querySelectorAll('.dropdown-menu .notification').forEach(function(notificationItem) {
    notificationItem.addEventListener('click', function() {
      let notificationId = this.getAttribute('data-id');
      
      fetch('{{ route('profile.notifikasi.update') }}', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          notification_id: notificationId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log('Notification marked as read');
        }
      })
      .catch(error => {
        console.error('Error updating notification:', error);
      });
    });
  });
</script>
@endpush