<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Data Champion Koi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalEditBody">
            <form action="{{ url('admin/banners') }}" method="post" id="formEdit" enctype="multipart/form-data">
            @csrf
            @method('PATCH')        
            <div class="form-group">
                <label for="edit_title">Title</label>
                <input  type="text" id="edit_title" class="form-control" name="edit_title" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_banner">Banner</label>
                <input type="file" name="edit_banner" id="edit_banner" class="form-control">
                <br>
                <img id="edit_banner2" src="" style="
                    width: 800px;
                    height: 200px;
                    object-fit: cover;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
          <button type="submit" id="btn-create" class="btn btn-primary">Simpan</button>
        </div>

        </form>
      </div>
    </div>
</div>
