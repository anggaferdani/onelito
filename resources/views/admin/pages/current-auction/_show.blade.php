<div class="modal fade" id="modalShow" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">History Bidding</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalShowBody">
        <div class="table-responsive">
            <table class="table table-bordered table-md">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Nominal Bidding</th>
                        <th>Waktu</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="cancelBidConfirm">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Bidding</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">×</span>
                       </button>
            </div>
        <div class="modal-body">           Anda akan menghapus data bidding</div>
            <div class="modal-footer">
                <button onclick="cancelLastBidding(this, 'process')" type="button" class="btn btn-danger btn-shadow" id="yesCancelBidding">Yes</button>
            </div>
        </div>
    </div>
</div>
