<div class="modal fade" id="modalShow" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail Champion Koi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalShowBody">
        <div class="form-group d-none">
                <label for="show_nama_champion">Nama Champion</label>
                <input readonly type="text" id="show_nama_champion" class="form-control" name="show_nama_champion" placeholder="">
            </div>
            <div class="form-group d-none">
                <label for="show_tahun">Tahun</label>
                <input readonly type="text" id="show_tahun" class="form-control" name="show_tahun" placeholder="">
            </div>
            <div class="form-group d-none">
                <label for="show_size">Size</label>
                <input readonly type="text" id="show_size" class="form-control" name="show_size" placeholder="">
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
