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
            <form action="{{ url('admin/champion-fishes') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="form-group d-none">
                <label for="nama_champion">Nama Champion</label>
                <input  type="text" id="nama_champion" class="form-control" name="nama_champion" placeholder="">
            </div>
            <div class="form-group d-none">
                <label for="tahun">Tahun</label>
                <input  type="text" id="tahun" class="form-control" name="tahun" placeholder="">
            </div>
            <div class="form-group d-none">
                <label for="size">Size</label>
                <input  type="text" id="size" class="form-control" name="size" placeholder="">
            </div>
            <div class="form-group">
                <label for="path_foto">Foto Ikan</label>
                <input type="file" name="path_foto" id="path_foto" class="form-control" required>
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
