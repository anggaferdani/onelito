<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Data Mata Uang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalEditBody">
                <form action="{{ url('admin/currencies') }}" method="post" id="formEdit"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="edit_name">Nama Mata Uang</label>
                        <input type="text" id="edit_name" class="form-control" name="edit_name" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="edit_symbol">Simbol</label>
                        <input type="text" id="edit_symbol" class="form-control" name="edit_symbol" placeholder="">
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
