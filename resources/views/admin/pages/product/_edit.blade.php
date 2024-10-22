<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Data Barang Store</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalEditBody">
            <form action="{{ url('admin/products') }}" method="post" id="formEdit" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="edit_id_kategori_produk">Kategori</label>
                <select id="edit_id_kategori_produk" name="edit_id_kategori_produk" class="form-control select2">
                    @forelse($categories as $category)
                        <option value="{{ $category->id_kategori_produk }}">{{ $category->kategori_produk }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <label for="edit_merek_produk">Merek</label>
                <input  type="text" id="edit_merek_produk" class="form-control" name="edit_merek_produk" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_nama_produk">Nama</label>
                <input  type="text" id="edit_nama_produk" class="form-control" name="edit_nama_produk" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_berat">Berat</label>
                <input  type="text" id="edit_berat" class="form-control" name="edit_berat" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_harga">Harga</label>
                <input  type="text" id="edit_harga" class="form-control" name="edit_harga" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_deskripsi">Deskripsi</label>
                <textarea  type="text" id="edit_deskripsi" class="form-control summernote" name="edit_deskripsi" placeholder=""></textarea>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
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
