@extends('admin.layouts.app')
@section('title', 'Setting')
@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Setting</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Setting</div>
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
                                  <th class="align-items-center text-center text-nowrap">Title</th>
                                  <th class="align-items-center">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($settings as $setting)
                                  <tr>
                                    <td class="align-items-center text-center text-nowrap">{{ ($settings->currentPage() - 1) * $settings->perPage() + $loop->iteration }}</td>
                                    <td class="align-items-center text-center text-nowrap">{{ $setting->title }}</td>
                                    <td class="align-items-center text-nowrap">
                                      <button type="button" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#editModal{{ $setting->id }}"><i class="fas fa-pen"></i></button>
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                  
                          <div class="float-right">
                            {{ $settings->appends(request()->query())->links('pagination::bootstrap-4') }}
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@foreach($settings as $setting)
<div class="modal fade" id="editModal{{ $setting->id }}" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" value="{{ $setting->title }}">
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label for="">Description <span class="text-danger">*</span></label>
            <textarea class="form-control summernote" name="description" rows="5" style="height: auto;">{{ $setting->description }}</textarea>
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
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
<script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
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
  
    $('.select3').select2({
      tags: true
    });

    $('.summernote').summernote({
      height: 250,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
      ],
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
