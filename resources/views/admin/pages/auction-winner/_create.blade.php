<div class="modal fade" id="modalCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Pemenang Lelang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalCreateBody">
            <form action="{{ url('admin/auction-winners') }}" method="post" id="formData" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="auction_product">Barang Lelang</label>
                <!-- <input  type="text" id="provinsi" class="form-control" name="provinsi" placeholder=""> -->
                <select name="auction_product" id="auction_product" required class="form-control select2 auction-product">
                    <option></option>
                    @foreach($auctionProducts as $product)
                        <option value="{{ $product->id_ikan }}">No Ikan: {{ $product->no_ikan }} | variety: {{ $product->variety }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_bidding">Pemenang Lelang</label>
                <!-- <input  type="text" id="provinsi" class="form-control" name="provinsi" placeholder=""> -->
                <select name="id_bidding" id="id_bidding" required class="form-control select2 id_bidding">
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
