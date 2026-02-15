<div class="modal fade" id="modalCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Champion Koi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalCreateBody">
            <form action="{{ url('admin/banners') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf           
            <div class="form-group">
                <label for="title">Title</label>
                <input  type="text" id="title" class="form-control" name="title" placeholder="">
            </div>
            <div class="form-group">
                <label for="banner">Banner <span class="text-danger">*</span></label>
                <input type="file" name="banner" id="banner" class="form-control" 
                      accept=".jpg,.jpeg,.png,.webp" required>
                <small class="form-text text-muted">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB</small>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
          <button type="submit" id="btn-create" class="btn btn-primary">Tambah</button>
        </div>

        </form>
      </div>
    </div>
</div>
