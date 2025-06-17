<div class="modal fade" id="modalShow" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail Fish</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalShowBody">
        <div class="form-group">
                <label for="show_no_ikan">No. Ikan</label>
                <input readonly type="text" id="show_no_ikan" class="form-control" name="show_no_ikan" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="show_variety">Variety</label>
                <input readonly type="text" id="show_variety" class="form-control" name="show_variety" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="show_breeder">Breeder</label>
                <input readonly type="text" id="show_breeder" class="form-control" name="show_breeder" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="show_bloodline">Bloodline</label>
                <input readonly type="text" id="show_bloodline" class="form-control" name="show_bloodline" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_sex">Jenis Kelamin</label>
                <input readonly type="show_sex" id="show_sex" class="form-control" name="show_sex" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_dob">DOB</label>
                <input readonly type="text" id="show_dob" class="form-control" name="show_dob" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_size">Size</label>
                <input readonly type="text" id="show_size" class="form-control" name="show_size" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_weight">Weight (gr)</label>
                <input  type="number" id="show_weight" class="form-control" name="show_weight" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_height">Height (cm)</label>
                <input  type="number" id="show_height" class="form-control" name="show_height" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_length">Length (cm)</label>
                <input  type="number" id="show_length" class="form-control" name="show_length" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_width">Width (cm)</label>
                <input  type="number" id="show_width" class="form-control" name="show_width" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_point">Point (Cashback)</label>
                <input readonly type="text" id="show_point" class="form-control" name="show_point" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_stock">Stock</label>
                <input readonly type="number" id="show_stock" class="form-control" name="show_stock" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_harga_ikan">Harga</label>
                <input readonly type="number" id="show_harga_ikan" class="form-control" name="show_harga_ikan" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="show_note">Deskripsi</label>
                <div id="show_note"></div>
            </div>
            <div class="form-group">
                <label for="show_link_video">Link Video</label>
                <input readonly type="text" id="show_link_video" class="form-control" name="show_link_video" placeholder="">
            </div>
            <div class="form-group">
                <label for="foto">Foto Ikan</label>
                <br>
                <img id="show_foto" src="" style="
                    width: 400px;
                    height: 400px;
                    object-fit: cover;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>
