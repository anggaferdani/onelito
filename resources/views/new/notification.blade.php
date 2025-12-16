@php
use App\Models\Notification;
use App\Models\SystemNotification;
use Illuminate\Support\Collection;

$auth = Auth::guard('member')->user();

$personalNotifications = Notification::where('peserta_id', $auth->id_peserta)
    ->whereNull('system_notification_id')
    ->get();

$systemNotifications = SystemNotification::where('status', 1)->get();

$readSystemIds = Notification::where('peserta_id', $auth->id_peserta)
    ->whereNotNull('system_notification_id')
    ->pluck('system_notification_id')
    ->toArray();

$notifications = collect();

foreach ($personalNotifications as $notif) {
    $notifications->push((object)[
        'id' => $notif->id,
        'label' => $notif->label,
        'description' => $notif->description,
        'link' => $notif->link,
        'created_at' => $notif->created_at,
        'status' => $notif->status,
        'peserta_id' => $notif->peserta_id,
        'system_notification_id' => null,
    ]);
}

foreach ($systemNotifications as $sys) {
    $isRead = in_array($sys->id, $readSystemIds);
    $notifications->push((object)[
        'id' => $sys->id,
        'label' => $sys->label,
        'description' => $sys->description,
        'link' => $sys->link,
        'created_at' => $sys->created_at,
        'status' => $isRead ? 0 : 1,
        'peserta_id' => null,
        'system_notification_id' => $sys->id,
    ]);
}

$notifications = $notifications->sortByDesc('created_at');
$unreadCount = $notifications->take(10)->filter(fn($n) => $n->status == 1)->count();
@endphp

@if($auth)
<li class="nav-item">
  <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
    <i class="fa-solid fa-bell fs-5"></i>
    @if($unreadCount > 0)
      <span class="badge bg-danger rounded-circle border border-white"
        style="font-size: 10px; transform: translate(-7px, -8px)" id="count">{{ $unreadCount }}</span>
    @endif
  </a>

  <div class="dropdown">
    <ul class="dropdown-menu dropdown-menu-end py-0"
      style="width: 400px !important; height: 500px; overflow-y: auto;">
      <li class="p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div class="fw-bold fs-5">Notification</div>
          <div><a href="{{ route('profile.notifikasi') }}" class="text-dark text-decoration-none">Lainnya</a></div>
        </div>
      </li>
      <hr class="m-0">

      @foreach($notifications->take(10) as $notification)
        <li class="p-3 notification"
            @if($notification->status == 1)
              style="background: RGBA(170, 217, 187, 0.2); cursor: pointer;"
            @endif
            data-id="{{ $notification->system_notification_id ?? $notification->id }}"
            data-type="{{ $notification->system_notification_id ? 'system' : 'personal' }}">
          <div class="card bg-transparent p-0 border-0">
            <div class="fw-bold mb-1">{{ $notification->label }}</div>
            <div class="small text-muted mb-1">{{ $notification->description }}</div>
            <div class="fw-bold small text-muted">
              {{ $notification->created_at?->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
            </div>
            @if($notification->link)
              <a href="{{ route('profile.notifikasi.click', [
                    'id' => $notification->id,
                    'type' => $notification->type
                ]) }}" class="stretched-link"></a>
            @endif
          </div>
        </li>

        @if(!$loop->last)
          <hr class="m-0">
        @endif
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
    let type = this.getAttribute('data-type');
    let notificationElement = this;

    fetch('{{ route('profile.notifikasi.update') }}', {
      method: 'PUT',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        notification_id: notificationId,
        type: type
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        notificationElement.style.background = 'transparent';
        let badge = document.getElementById('count');
        if (badge) {
          let count = parseInt(badge.textContent);
          if (count > 1) badge.textContent = count - 1;
          else badge.remove();
        }
      }
    })
    .catch(error => console.error('Error updating notification:', error));
  });
});
</script>
@endpush
