<div class="modal fade" id="modalShow" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail Auction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalShowBody">
            <div class="form-group">
                <label for="show_kategori_event">Kategori Event</label>
                <input readonly type="text" id="show_kategori_event" class="form-control" name="show_kategori_event" placeholder="" required>
            </div>
            <div class="form-group d-none">
                <label for="show_deskripsi_event">Deskripsi</label>
                <input readonly  type="text" id="show_deskripsi_event" class="form-control" name="show_deskripsi_event" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="show_rules_event">Rules</label>
                <div id="show_rules_event"></div>
                <!-- <textarea readonly id="show_rules_event" name="show_rules_event" class="form-control" placeholder="" required></textarea> -->
                <!-- <input  type="text" id="show_rules_events" class="form-control" name="show_rules_events" placeholder="" required> -->
            </div>
            <div class="form-group">
                <label for="show_tgl_mulai">Tgl. Mulai</label>
                <input readonly  type="text" id="show_tgl_mulai" class="form-control" name="show_tgl_mulai" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="show_tgl_akhir">Tgl. Akhir</label>
                <input readonly  type="text" id="show_tgl_akhir" class="form-control" name="show_tgl_akhir" placeholder="">
            </div>
            <div class="form-group">
                <label for="show_banner">Banner</label>
                <br>
                <img id="show_banner" src="" style="
                    width: 400px;
                    height: 400px;
                    object-fit: cover;">
            </div>
            <div class="form-group">
                <label for="show_total_hadiah">Total Hadiah</label>
                <input readonly  type="number" id="show_total_hadiah" class="form-control" name="show_total_hadiah" placeholder="">
            </div>
            <div class="form-group">
                <label>Barang Lelang</label>
                <br>
                <div class="row gutters-sm" id="show_auction_products">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>
