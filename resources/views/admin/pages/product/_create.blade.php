<div class="modal fade" id="modalCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Barang Store</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalCreateBody">
            <form action="{{ url('admin/products') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            <label for="id_kategori_produk">Kategori</label>
                <select id="id_kategori_produk" name="id_kategori_produk" class="form-control select2">
                    @forelse($categories as $category)
                        <option value="{{ $category->id_kategori_produk }}">{{ $category->kategori_produk }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <label for="merek_produk">Merek</label>
                <input  type="text" id="merek_produk" class="form-control" name="merek_produk" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="nama_produk">Nama</label>
                <input  type="text" id="nama_produk" class="form-control" name="nama_produk" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="berat">Berat</label>
                <input  type="text" id="berat" class="form-control" name="berat" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight (gr)</label>
                <input  type="number" id="weight" class="form-control" name="weight" placeholder="">
            </div>
            <div class="form-group">
                <label for="height">Height (cm)</label>
                <input  type="number" id="height" class="form-control" name="height" placeholder="">
            </div>
            <div class="form-group">
                <label for="length">Length (cm)</label>
                <input  type="number" id="length" class="form-control" name="length" placeholder="">
            </div>
            <div class="form-group">
                <label for="width">Width (cm)</label>
                <input  type="number" id="width" class="form-control" name="width" placeholder="">
            </div>
            
            <div class="form-group">
                <label for="stock">Stock</label>
                <input  type="number" id="stock" class="form-control" name="stock" placeholder="">
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input  type="text" id="harga" class="form-control" name="harga" placeholder="">
            </div>
            <div class="form-group">
                <label for="percent">Percent (Max: 100)</label>
                <input  type="number" id="percent" class="form-control" name="percent" placeholder="">
            </div>
            <div class="form-group">
                <label for="point">Point (Cashback)</label>
                <input  type="text" id="point" class="form-control" name="point" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea  type="text" id="deskripsi" class="form-control summernote" name="deskripsi" placeholder=""></textarea>
            </div>
            <div class="form-group">
                <label for="path_foto">Foto</label>
                <input type="file" name="path_foto" id="path_foto" class="form-control">
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