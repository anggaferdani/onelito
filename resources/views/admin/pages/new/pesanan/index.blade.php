@extends('admin.layouts.app')
@section('title', 'Pesanan')
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Management Pesanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Management Pesanan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <div class="float-left">
                          </div>
                          <div class="float-right">
                            <form id="filter" action="{{ route('admin.news.index') }}" method="GET">
                              <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="search" id="search" value="">
                              </div>
                            </form>
                          </div>
                  
                          <div class="clearfix mb-3"></div>

                          <div class="table-responsive">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th class="align-items-center text-center text-nowrap">No.</th>
                                  <th class="align-items-center text-center text-nowrap">No Order</th>
                                  <th class="align-items-center text-center text-nowrap">Tanggal</th>
                                  <th class="align-items-center text-center text-nowrap">Nama Penerima</th>
                                  <th class="align-items-center text-center text-nowrap">Total Tagihan</th>
                                  <th class="align-items-center text-center text-nowrap">Status</th>
                                  <th class="align-items-center">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($orders as $order)
                                  <tr>
                                    <td class="align-items-center text-center text-nowrap">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                    <td class="align-items-center text-center text-nowrap">{{ $order->no_order }}</td>
                                    <td class="align-items-center text-center text-nowrap">{{ \Carbon\Carbon::parse($order->tanggal)->format('Y M d H:i:s') }}</td>
                                    <td class="align-items-center text-center text-nowrap">{{ $order->destination_contact_name }}</td>
                                    <td class="align-items-center text-center text-nowrap">{{ 'Rp. ' . number_format($order->total_tagihan, 0, '.', '.') }}</td>
                                    <td class="align-items-center text-center text-nowrap">
                                      @if($order->status_order == 'pending')
                                        <span class="badge badge-secondary">Menunggu Pembayaran</span>
                                      @elseif($order->status_order == 'paid')
                                        <span class="badge badge-primary">Menunggu Konfirmasi</span>
                                      @elseif($order->status_order == 'process')
                                        <span class="badge badge-warning">Sedang Diproses</span>
                                      @elseif($order->status_order == 'delivered')
                                        <span class="badge badge-info">Sedang Dalam Pengiriman</span>
                                      @elseif($order->status_order == 'done')
                                        <span class="badge badge-success">Selesai</span>
                                      @elseif($order->status_order == 'cancel')
                                        <span class="badge badge-danger">Dibatalkan System</span>
                                      @elseif($order->status_order == 'cancel' && $order->dibatalkan_pembeli == 1)
                                        <span class="badge badge-danger">Dibatalkan Pembeli</span>
                                      @endif
                                    </td>
                                    <td class="align-items-center text-nowrap">
                                      <a href="{{ route('admin.pesanan.detail', $order->no_order) }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-eye"></i></a>
                                      @csrf
                                      @method('DELETE')
                                      @if($order->status_order == 'paid')
                                        <a href="{{ route('admin.pesanan.process', $order->no_order) }}" class="btn btn-warning">Process</a>
                                      @elseif($order->status_order == 'process')
                                        <a href="{{ route('admin.pesanan.kirim', $order->no_order) }}" class="btn btn-primary">Kirim</a>
                                      @endif
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                  
                          <div class="float-right">
                            {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function() {
      document.getElementById('search').addEventListener('input', function() {
          document.getElementById('filter').submit();
      });
  });
</script>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  const searchQuery = urlParams.get('search');

  document.addEventListener("DOMContentLoaded", function() {
      const searchInput = document.getElementById('search');

      if (searchQuery) {
          searchInput.value = searchQuery;
      }
  });
</script>
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.all.js" integrity="sha512-AINSNy+d2WG9ts1uJvi8LZS42S8DT52ceWey5shLQ9ArCmIFVi84nXNrvWyJ6bJ+qIb1MnXR46+A4ic/AUcizQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.select2').select2({});
  });

  $(document).ready(function(){
    $('.select3').select2({
      tags: true
    });
  });

  $('.delete').click(function(){
    Swal.fire({
      title: "Are you sure?",
      text: "Are you sure you want to delete this item?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, delete it",
      closeOnConfirm: false
    }).then((result) => {
      if(result.isConfirmed){
        $(this).closest("form").submit();
        Swal.fire(
          'Deleted',
          'You have successfully deleted',
          'success',
        );
      }
    });
  });

  $('.delete2').click(function(event){
    event.preventDefault();
    var deleteUrl = $(this).attr('href');
    Swal.fire({
      title: "Are you sure?",
      text: "Are you sure you want to delete this item?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, delete it",
      closeOnConfirm: false
    }).then((result) => {
      if(result.isConfirmed){
          window.location.href = deleteUrl;
          Swal.fire(
            'Deleted',
            'You have successfully deleted',
            'success',
          );
      }
    });
  });
</script>
@endpush
