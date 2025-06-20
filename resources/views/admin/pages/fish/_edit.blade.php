<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Data Fish</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="modalEditBody">
            {{-- Perhatikan name atribut sudah diubah --}}
            <form action="" method="post" id="formEdit" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="edit_no_ikan">No. Ikan</label>
                <input  type="text" id="edit_no_ikan" class="form-control" name="no_ikan" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_variety">Variety</label>
                <input  type="text" id="edit_variety" class="form-control" name="variety" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_breeder">Breeder</label>
                <input  type="text" id="edit_breeder" class="form-control" name="breeder" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_bloodline">Bloodline</label>
                <input  type="text" id="edit_bloodline" class="form-control" name="bloodline" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_sex">Jenis Kelamin</label>
                <select id="edit_sex" name="sex" class="form-control">
                    {{-- Konten diisi oleh Javascript --}}
                </select >
            </div>
            <div class="form-group">
                <label for="edit_dob">DOB</label>
                <input  type="text" id="edit_dob" class="form-control" name="dob" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_size">Size</label>
                <input  type="text" id="edit_size" class="form-control" name="size" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_weight">Weight (gr)</label>
                <input  type="number" id="edit_weight" class="form-control" name="weight" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_height">Height (cm)</label>
                <input  type="number" id="edit_height" class="form-control" name="height" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_length">Length (cm)</label>
                <input  type="number" id="edit_length" class="form-control" name="length" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_width">Width (cm)</label>
                <input  type="number" id="edit_width" class="form-control" name="width" placeholder="">
            </div>
            
            <div class="form-group">
                <label for="edit_stock">Stock</label>
                <input  type="number" id="edit_stock" class="form-control" name="stock" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_harga_ikan">Harga</label>
                <input  type="text" id="edit_harga_ikan" class="form-control" name="harga_ikan" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_percent">Percent (Max: 100)</label>
                {{-- Input ini hanya untuk kalkulasi di frontend, jadi name bisa dikosongkan --}}
                <input  type="number" id="edit_percent" class="form-control" name="" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_point">Point (Cashback)</label>
                <input  type="text" id="edit_point" class="form-control" name="point" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="edit_note">Deskripsi</label>
                <textarea id="edit_note" name="note" class="form-control tinymce" placeholder=""></textarea>
            </div>
            <div class="form-group">
                <label for="edit_link_video">Link Video</label>
                <input  type="text" id="edit_link_video" name="link_video" class="form-control" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_foto">Foto Ikan</label>
                <input type="file" name="foto_ikan" id="edit_foto" class="form-control">
                <br>
                <img id="edit_foto2" src="" style="
                    width: 400px;
                    height: 400px;
                    object-fit: cover;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
          <button type="submit" id="btn-update" class="btn btn-primary">Simpan Perubahan</button>
        </div>

        </form>
      </div>
    </div>
</div>