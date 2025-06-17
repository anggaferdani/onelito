<div class="modal fade" id="modalCreate" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalCreateBody">
            <form action="{{ url('admin/members') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input  type="text" id="nama" class="form-control" name="nama" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input  type="email" id="email" class="form-control" name="email" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input  type="password" id="password" class="form-control" name="password" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No. HP</label>
                <input  type="text" id="no_hp" class="form-control" name="no_hp" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input  type="text" id="alamat" class="form-control" name="alamat" placeholder="">
            </div>
            <div class="form-group">
                <label for="provinsi">Provinsi</label>
                <!-- <input  type="text" id="provinsi" class="form-control" name="provinsi" placeholder=""> -->
                <select name="provinsi" id="provinsi" required class="form-control select2">
                    <option></option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->prov_id }}">{{ $province->prov_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="kota">Kota</label>
                <select name="kota" id="kota" required class="form-control select2">
                    <option></option>
                </select>
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" required class="form-control select2">
                    <option></option>
                </select>
            </div>
            <div class="form-group">
                <label for="kelurahan">Kelurahan</label>
                <select name="kelurahan" id="kelurahan" required class="form-control select2">
                    <option></option>
                </select>
            </div>
            </br>
            <div class="form-group">
                <label for="kode_pos">Kode Pos</label>
                <input  type="text" id="kode_pos" class="form-control" name="kode_pos" placeholder="">
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
