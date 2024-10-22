<div class="modal fade" id="modalShow" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail Pemenang Lelang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalShowBody">
            <section class="section">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- <div class="invoice-title"> -->
                                    <!-- <div class="invoice-number float-left">Order #12345</div> -->
                                <!-- </div> -->
                                <hr class="mt-2 mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Dikirim ke:</strong><br>
                                            <div id="send_to">
                                            Ujang Maman<br>
                                            1234 Main<br>
                                            Apt. 4B<br>
                                            Bogor Barat, Indonesia
                                            </div>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <!-- <strong>Tanggal Pembelian:</strong><br> -->
                                            <!-- <span id="tanggal_order">September 19, 2018</span><br><br> -->
                                        </address>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Payment Method:</strong><br>
                                            Visa ending **** 4242<br>
                                            ujang@maman.com
                                        </address>
                                    </div>

                                </div> -->
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <!-- <div class="section-title">Detail Produk</div> -->
                                <!-- <p class="section-lead">All items here cannot be deleted.</p> -->
                                <div class="table-responsive">
                                    <table id="table_detail" class="table table-striped table-hover table-md">
                                        <tr>
                                            <th data-width="40">#</th>
                                            <th class="">Ketegori</th>
                                            <th>Merek</th>
                                            <th class="">Nama</th>
                                            <th class="">Harga</th>
                                            <th class="">Jumlah</th>
                                            <th class="">Total</th>
                                        </tr>
                                        <!-- <tr>
                                            <td>1</td>
                                            <td>Mouse Wireless</td>
                                            <td class="text-center">$10.99</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">$10.99</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Keyboard Wireless</td>
                                            <td class="text-center">$20.00</td>
                                            <td class="text-center">3</td>
                                            <td class="text-right">$60.00</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Headphone Blitz TDR-3000</td>
                                            <td class="text-center">$600.00</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">$600.00</td>
                                        </tr> -->
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-8">
                                        <!-- <div class="section-title">Payment Method</div>
                                        <p class="section-lead">The payment method that we provide is to make it easier for
                                            you to pay invoices.</p>
                                        <div class="images">
                                            <img src="{{ asset('img/payment/visa.png') }}"
                                                alt="visa">
                                            <img src="{{ asset('img/payment/jcb.png') }}"
                                                alt="jcb">
                                            <img src="{{ asset('img/payment/mastercard.png') }}"
                                                alt="mastercard">
                                            <img src="{{ asset('img/payment/paypal.png') }}"
                                                alt="paypal">
                                        </div> -->
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <!-- <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Subtotal</div>
                                            <div class="invoice-detail-value">$670.99</div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Shipping</div>
                                            <div class="invoice-detail-value">$15</div>
                                        </div>
                                        <hr class="mt-2 mb-2"> -->
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Total</div>
                                            <div id="total" class="invoice-detail-value invoice-detail-value-lg">$685.99</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- <div class="text-md-right">
                        <div class="float-lg-left mb-lg-0 mb-3">
                            <button class="btn btn-primary btn-icon icon-left"><i class="fas fa-credit-card"></i> Process
                                Payment</button>
                            <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                        <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                    </div> -->
                </div>
            </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
</div>
