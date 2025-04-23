@extends('admin.layouts.app')
@section('title', 'Label')
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Management Label</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Management Label</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <div class="float-left">
                            <button type="button" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i></button>
                          </div>
                          <div class="float-right">
                            <form id="filter" action="{{ route('admin.label.index') }}" method="GET">
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
                                  <th class="align-items-center text-center text-nowrap">Label</th>
                                  <th class="align-items-center">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($labels as $label)
                                  <tr>
                                    <td class="align-items-center text-center text-nowrap">{{ ($labels->currentPage() - 1) * $labels->perPage() + $loop->iteration }}</td>
                                    <td class="align-items-center text-center text-nowrap">{{ $label->label }}</td>
                                    <td class="align-items-center text-nowrap">
                                      <form action="{{ route('admin.label.destroy', $label->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#editModal{{ $label->id }}"><i class="fas fa-pen"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger delete"><i class="fas fa-trash"></i></button>
                                      </form>
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                  
                          <div class="float-right">
                            {{ $labels->appends(request()->query())->links('pagination::bootstrap-4') }}
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="createModal" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.label.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="">Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="label">
            @error('label')<div class="text-danger">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($labels as $label)
<div class="modal fade" id="editModal{{ $label->id }}" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.label.update', $label->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="">Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="label" value="{{ $label->label }}">
            @error('label')<div class="text-danger">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
<script src="{{ asset('stisla/assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.all.js" integrity="sha512-AINSNy+d2WG9ts1uJvi8LZS42S8DT52ceWey5shLQ9ArCmIFVi84nXNrvWyJ6bJ+qIb1MnXR46+A4ic/AUcizQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.select2').select2({});
  });

  $(document).ready(function(){
    $('.select3').select2({
      labels: true
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
