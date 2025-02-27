@extends('new.templates.profile')
@section('title', 'Alamat')
@section('content')
<div class="row align-items-center g-2 mb-3">
  <div class="col-md-6">
    <form id="searchForm" action="{{ route('alamat.index') }}" method="GET">
      <input type="text" id="searchInput" name="search" class="form-control rounded" placeholder="Tulis label / nama / alamat penerima" value="{{ request('search') }}">
    </form>
  </div>
  <div class="col-md-4 ms-auto"><button type="" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Alamat Baru</button></div>
</div>
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
<div class="row g-2">
  @foreach($alamats as $alamat)
    <div class="col-12">
      <div class="card @if($auth->pilih_alamat == $alamat->id) border-danger alert-danger @endif">
        <div class="card-body p-4">
          <div class="row g-3 align-items-center">
            <div class="col-md-10">
              <div class="fw-bold small mb-1">{{ $alamat->label }} @if($auth->alamat_utama == $alamat->id) <span class="badge bg-secondary">Utama</span> @endif</div>
              <div class="fw-bold mb-1">{{ $alamat->nama }}</div>
              <div class="mb-1">{{ $alamat->no_hp }}</div>
              <div class="mb-1">{{ $alamat->alamat_lengkap }}</div>
              <div class="d-md-flex align-items-center d-block gap-2">
                <div class="text-danger small" data-bs-toggle="modal" data-bs-target="#editModal{{ $alamat->id }}">Ubah Alamat</div>
                <div class="text-danger small d-none d-md-flex">|</div>
                @if($auth->alamat_utama != $alamat->id)
                  <a href="{{ route('alamat.alamat-utama', ['alamatId' => $alamat->id]) }}" class="text-danger small text-decoration-none">Jadikan Alamat Utama</a>
                  <div class="text-danger small d-none d-md-flex">|</div>
                @endif
                <form action="{{ route('alamat.destroy', $alamat->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="border-0 bg-transparent text-danger small delete p-0">Hapus</button>
                </form>
              </div>
            </div>
            <div class="col-md-2 @if($auth->pilih_alamat == $alamat->id) d-none d-md-flex align-items-center @endif">
              @if($auth->pilih_alamat == $alamat->id)
                <div class="m-auto"><i class="fa-solid fa-check text-danger"></i></div>
              @else
                <a href="{{ route('alamat.pilih-alamat', ['alamatId' => $alamat->id]) }}" class="btn btn-danger d-none d-md-block">Pilih</a>
                <a href="{{ route('alamat.pilih-alamat', ['alamatId' => $alamat->id]) }}" class="btn btn-danger w-100 d-block d-md-none">Pilih</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <div class="d-flex justify-content-end">{{ $alamats->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
</div>

<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Create</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('alamat.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="label" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-muted">(optional)</span></label>
            <input type="email" class="form-control" name="email">
          </div>
          <div class="mb-3">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="no_hp" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" name="alamat_lengkap" required></textarea>
            <div class="text-danger small lh-sm mt-2">Isi dengan alamat lengkap. Contoh : Cinere Residence F5 / 30  Meruyung, Limo,  Depok, Jawa Barat</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Catatan <span class="text-muted">(optional)</span></label>
            <textarea class="form-control" rows="3" name="catatan"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode Pos <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="kode_pos" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Pin Point Lokasi <span class="text-danger">*</span></label>
            <div id="map" style="height: 300px;"></div>
            <div class="text-danger small lh-sm mt-2">Pastikan lokasi pin point sudah sesuai *Disarankan menggunakan handphone agar titik pin point lebih akurat.</div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
          </div>
          <div id="location-alert" class="alert alert-success d-none" role="alert">
            Berhasil get lokasi pada <span id="location-time"></span>
          </div>
          <div>
            <button class="btn btn-primary getCurrentLocation w-100"><i class="fa-solid fa-location-crosshairs"></i> Gunakan lokasi saat ini</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($alamats as $alamat)
<div class="modal fade" id="editModal{{ $alamat->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('alamat.update', $alamat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="label" required value="{{ $alamat->label }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama" required value="{{ $alamat->nama }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-muted">(optional)</span></label>
            <input type="email" class="form-control" name="email" value="{{ $alamat->email }}">
          </div>
          <div class="mb-3">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="no_hp" required value="{{ $alamat->no_hp }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" name="alamat_lengkap" required>{{ $alamat->alamat_lengkap }}</textarea>
            <div class="text-danger small lh-sm mt-2">Isi dengan alamat lengkap. Contoh : Cinere Residence F5 / 30  Meruyung, Limo,  Depok, Jawa Barat</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Catatan <span class="text-muted">(optional)</span></label>
            <textarea class="form-control" rows="3" name="catatan">{{ $alamat->catatan }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode Pos <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="kode_pos" value="{{ $alamat->kode_pos }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Pin Point Lokasi <span class="text-danger">*</span></label>
            <div id="map-edit{{ $alamat->id }}" style="height: 300px;"></div>
            <div class="text-danger small lh-sm mt-2">Pastikan lokasi pin point sudah sesuai *Disarankan menggunakan handphone agar titik pin point lebih akurat.</div>
            <input type="hidden" id="latitude-edit{{ $alamat->id }}" name="latitude" value="{{ $alamat->latitude }}">
            <input type="hidden" id="longitude-edit{{ $alamat->id }}" name="longitude" value="{{ $alamat->longitude }}">
          </div>
          <div id="location-alert-edit{{ $alamat->id }}" class="alert alert-success d-none" role="alert">
            Berhasil get lokasi pada <span id="location-time-edit{{ $alamat->id }}"></span>
          </div>
          <div>
            <button class="btn btn-primary getCurrentLocationEdit w-100" data-id="{{ $alamat->id }}"><i class="fa-solid fa-location-crosshairs"></i> Gunakan lokasi saat ini</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
@push('scripts')
<script>
  document.getElementById('searchInput').addEventListener('keydown', function(event) {
      if (event.key === 'Enter') {
          event.preventDefault();
          document.getElementById('searchForm').submit();
      }
  });
</script>
<script>
  let map, marker;

  function initMap() {
      map = L.map('map').setView([-6.200000, 106.816666], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '© OpenStreetMap contributors'
      }).addTo(map);

      map.on('click', function(e) {
          const { lat, lng } = e.latlng;

          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lng;

          if (marker) {
              marker.setLatLng(e.latlng);
          } else {
              marker = L.marker(e.latlng).addTo(map);
          }
      });
  }

  // Function to get current location with high accuracy
  function setCurrentLocation() {
    if (navigator.geolocation) {
      const options = {
        enableHighAccuracy: true, // Request high accuracy
        timeout: 10000,           // Set a timeout of 10 seconds
        maximumAge: 0            // Don't use a cached location
      };

      navigator.geolocation.getCurrentPosition(
        function(position) {
          const { latitude, longitude } = position.coords;
          document.getElementById('latitude').value = latitude;
          document.getElementById('longitude').value = longitude;

          map.setView([latitude, longitude], 15);

          if (marker) {
            marker.setLatLng([latitude, longitude]);
          } else {
            marker = L.marker([latitude, longitude]).addTo(map);
          }

          // Show success alert with timestamp
          const now = new Date();
          const timeString = now.toLocaleTimeString();
          document.getElementById('location-time').innerText = timeString;
          document.getElementById('location-alert').classList.remove('d-none');
        },
        function(error) {
          // Handle errors more gracefully
          let errorMessage = 'Error retrieving location. ';
          switch (error.code) {
            case error.PERMISSION_DENIED:
              errorMessage += "Please allow location access to use this feature.";
              break;
            case error.POSITION_UNAVAILABLE:
              errorMessage += "Location information is unavailable.";
              break;
            case error.TIMEOUT:
              errorMessage += "The request to get location timed out.  Try again.";
              break;
            case error.UNKNOWN_ERROR:
              errorMessage += "An unknown error occurred.";
              break;
          }
          alert(errorMessage);
        },
        options // Pass the accuracy options
      );
    } else {
      alert('Geolocation is not supported by this browser.');
    }
  }


  document.querySelector('.getCurrentLocation').addEventListener('click', function(event) {
      event.preventDefault();
      setCurrentLocation();
  });

  $('#createModal').on('shown.bs.modal', function () {
      setTimeout(() => {
          initMap();
      }, 200);
  });

  @foreach($alamats as $alamat)
      $('#editModal{{ $alamat->id }}').on('shown.bs.modal', function () {
          setTimeout(() => {
              const mapEdit = L.map('map-edit{{ $alamat->id }}').setView([{{ $alamat->latitude ?? '-6.200000' }}, {{ $alamat->longitude ?? '106.816666' }}], 15);

              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  maxZoom: 19,
                  attribution: '© OpenStreetMap contributors'
              }).addTo(mapEdit);

              let markerEdit = L.marker([{{ $alamat->latitude ?? '-6.200000' }}, {{ $alamat->longitude ?? '106.816666' }}]).addTo(mapEdit);

              mapEdit.on('click', function(e) {
                  const { lat, lng } = e.latlng;

                  document.getElementById('latitude-edit{{ $alamat->id }}').value = lat;
                  document.getElementById('longitude-edit{{ $alamat->id }}').value = lng;

                  markerEdit.setLatLng(e.latlng);
              });

              document.querySelector('.getCurrentLocationEdit[data-id="{{ $alamat->id }}"]').addEventListener('click', function(event) {
                  event.preventDefault();

                  // Function to get current location with high accuracy
                  function setCurrentLocationEdit(map, marker, latitudeInputId, longitudeInputId, alertId, timeId) {
                      if (navigator.geolocation) {
                          const options = {
                              enableHighAccuracy: true,
                              timeout: 10000,
                              maximumAge: 0
                          };

                          navigator.geolocation.getCurrentPosition(
                              function(position) {
                                  const { latitude, longitude } = position.coords;
                                  document.getElementById(latitudeInputId).value = latitude;
                                  document.getElementById(longitudeInputId).value = longitude;

                                  map.setView([latitude, longitude], 15);

                                  if (marker) {
                                      marker.setLatLng([latitude, longitude]);
                                  } else {
                                      marker = L.marker([latitude, longitude]).addTo(map);
                                  }

                                   // Show success alert with timestamp
                                  const now = new Date();
                                  const timeString = now.toLocaleTimeString();
                                  document.getElementById(timeId).innerText = timeString;
                                  document.getElementById(alertId).classList.remove('d-none');
                              },
                              function(error) {
                                  // Handle errors
                                  let errorMessage = 'Error retrieving location. ';
                                  switch (error.code) {
                                      case error.PERMISSION_DENIED:
                                          errorMessage += "Please allow location access to use this feature.";
                                          break;
                                      case error.POSITION_UNAVAILABLE:
                                          errorMessage += "Location information is unavailable.";
                                          break;
                                      case error.TIMEOUT:
                                          errorMessage += "The request to get location timed out.  Try again.";
                                          break;
                                      case error.UNKNOWN_ERROR:
                                          errorMessage += "An unknown error occurred.";
                                          break;
                                  }
                                  alert(errorMessage);
                              },
                              options
                          );
                      } else {
                          alert('Geolocation is not supported by this browser.');
                      }
                  }

                  setCurrentLocationEdit(mapEdit, markerEdit, 'latitude-edit{{ $alamat->id }}', 'longitude-edit{{ $alamat->id }}', 'location-alert-edit{{ $alamat->id }}', 'location-time-edit{{ $alamat->id }}');
              });
          }, 200);
      });
  @endforeach
</script>
@endpush