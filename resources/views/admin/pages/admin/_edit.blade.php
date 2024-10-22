<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Data Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalEditBody">
            <form action="" method="POST" id="formEdit" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="edit_nama">Nama</label>
                    <input  type="text" id="edit_nama" class="form-control" name="edit_nama" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input  type="text" id="edit_username" class="form-control" name="edit_username" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input  type="email" id="edit_email" class="form-control" name="edit_email" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="edit_no_hp">No. HP</label>
                    <input  type="text" id="edit_no_hp" class="form-control" name="edit_no_hp" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="edit_alamat">Alamat</label>
                    <input  type="text" id="edit_alamat" class="form-control" name="edit_alamat" placeholder="">
                </div>
                <div class="form-group">
                    <label for="edit_kota">Kota</label>
                    <input  type="text" id="edit_kota" class="form-control" name="edit_alamat" placeholder="">
                </div>
                <div class="form-group">
                    <label for="edit_level">Level</label>
                    <select id="edit_level" name="edit_level" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
          <button type="submit" id="save-edit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
      </div>
    </div>
</div>
