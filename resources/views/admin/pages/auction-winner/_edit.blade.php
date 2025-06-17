<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Pemenang Lelang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalEditBody">
            <form action="{{ url('admin/champion-fishes') }}" method="post" id="formEdit" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="edit_nama_champion">Nama Champion</label>
                <input  type="text" id="edit_nama_champion" class="form-control" name="edit_nama_champion" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_tahun">Tahun</label>
                <input  type="text" id="edit_tahun" class="form-control" name="edit_tahun" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_size">Size</label>
                <input  type="text" id="edit_size" class="form-control" name="edit_size" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_foto">Foto Ikan</label>
                <input type="file" name="edit_foto" id="edit_foto" class="form-control">
                <br>
                <img id="edit_foto2" src="" style="
                    width: 400px;
                    height: 400px;
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
