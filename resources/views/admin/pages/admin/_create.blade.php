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
            <form action="{{ url('admin/admins') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input  type="text" id="nama" class="form-control" name="nama" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input  type="text" id="username" class="form-control" name="email" placeholder="" required>
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
                <label for="kota">Kota</label>
                <input  type="text" id="kota" class="form-control" name="alamat" placeholder="">
            </div>
            <div class="form-group">
                <label for="level">Level</label>
                <select id="level" name="level" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
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
