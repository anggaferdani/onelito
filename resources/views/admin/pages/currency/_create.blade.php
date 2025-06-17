<div class="modal fade" id="modalCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Mata Uang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalCreateBody">
                <form action="{{ url('admin/currencies') }}" method="post" id="formData"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Mata Uang</label>
                        <input type="text" id="name" class="form-control" name="name" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="symbol">Simbol</label>
                        <input type="text" id="symbol" class="form-control" name="symbol" placeholder="">
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
