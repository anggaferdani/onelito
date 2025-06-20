<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Data Auction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalEditBody">
            <form action="{{ url('admin/auctions') }}" method="post" id="formEdit" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="edit_kategori_event">Kategori Event</label>
                <select id="edit_kategori_event" name="edit_kategori_event" class="form-control">
                    <option value="Regular">Regular</option>
                    <option value="Event">Event</option>
                </select>
            </div>
            <div class="form-group d-none">
                <label for="edit_deskripsi_event">Deskripsi</label>
                <input  type="text" id="edit_deskripsi_event" class="form-control" name="edit_deskripsi_event" placeholder="">
            </div>
            <div class="form-group">
                <label for="edit_rules_event">Rules</label>
                <textarea id="edit_rules_event" name="edit_rules_event" class="form-control tinymce" placeholder=""></textarea>
                <!-- <input  type="text" id="rules_events" class="form-control" name="rules_events" placeholder="" required> -->
            </div>
            <div class="form-group">
                <label for="edit_tgl_mulai">Tgl. Mulai</label>
                <input  type="text" id="edit_tgl_mulai" class="form-control datetimepicker" name="edit_tgl_mulai" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="edit_tgl_akhir">Tgl. Akhir</label>
                <input  type="text" id="edit_tgl_akhir" class="form-control datetimepicker" name="edit_tgl_akhir" placeholder="">
            </div>
            <div class="form-group banner">
                <label for="edit_banner">Banner</label>
                <input type="file" name="edit_banner" id="edit_banner" class="form-control">
                <br>
                <img id="edit_banner2" src="" style="
                    width: 400px;
                    height: 400px;
                    object-fit: cover;">
            </div>
            <div class="form-group total_hadiah d-none">
                <label for="edit_total_hadiah">Total Hadiah</label>
                <input  type="text" id="edit_total_hadiah" class="form-control" name="edit_total_hadiah" placeholder="">
            </div>
            <div class="form-group">
            <label for="edit_auction_products">Barang Lelang</label>
                <select id="edit_auction_products" name="edit_auction_products[]" class="form-control select2"
                    multiple="">
                    @forelse($auctionProducts as $product)
                        <option class="{{ $product->id_ikan }}" value="{{ $product->id_ikan }}">No Ikan: {{ $product->no_ikan }} | variety: {{ $product->variety }}</option>
                    @empty

                    @endforelse
                </select>
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
