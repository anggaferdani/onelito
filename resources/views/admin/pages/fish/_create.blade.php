<div class="modal fade" id="modalCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Fish</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalCreateBody">
            <form action="{{ url('admin/fishes') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="no_ikan">No. Ikan</label>
                <input  type="text" id="no_ikan" class="form-control" name="no_ikan" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="variety">Variety</label>
                <input  type="text" id="variety" class="form-control" name="variety" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="breeder">Breeder</label>
                <input  type="text" id="breeder" class="form-control" name="breeder" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="bloodline">Bloodline</label>
                <input  type="text" id="bloodline" class="form-control" name="bloodline" placeholder="">
            </div>
            <div class="form-group">
                <label for="sex">Jenis Kelamin</label>
                <select id="sex" name="sex" class="form-control">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Unknown">Unknown</option>
                </select >
            </div>
            <div class="form-group">
                <label for="dob">DOB</label>
                <input  type="text" id="dob" class="form-control datepicker" name="dob" placeholder="">
            </div>
            <div class="form-group">
                <label for="size">Size</label>
                <input  type="text" id="size" class="form-control" name="size" placeholder="">
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
                <label for="harga_ikan">Harga</label>
                <input  type="text" id="harga_ikan" class="form-control" name="harga_ikan" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="percent">Percent (Max: 100)</label>
                <input  type="number" id="percent" class="form-control" name="" placeholder="">
            </div>
            <div class="form-group">
                <label for="point">Point (Cashback)</label>
                <input  type="text" id="point" class="form-control" name="point" placeholder="" readonly>
            </div>
            <div class="form-group">
                <label for="note">Deskripsi</label>
                <textarea id="note" name="note" class="form-control summernote" placeholder=""></textarea>
            </div>
            <div class="form-group">
                <label for="link_video">Link Video</label>
                <input  type="text" id="link_video" name="link_video" class="form-control" placeholder="">
            </div>
            <div class="form-group">
                <label for="path_foto">Foto Ikan</label>
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