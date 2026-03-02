@extends('admin.layouts.app')

@section('title', 'Current Auction')

@push('style')
    <link rel="stylesheet"
        href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        /* ── History Bidding Modal ── */
        #modalShow .modal-header {
            background: #1a1a2e;
            color: #fff;
            border-bottom: 2px solid #dc3545;
        }

        #modalShow .modal-header .close {
            color: #fff;
            opacity: 0.8;
        }

        #modalShow .modal-header .close:hover {
            opacity: 1;
        }

        #modalShow .modal-body {
            background: #12121f;
            padding: 0;
        }

        #modalShow .table-responsive {
            max-height: 420px;
            overflow-y: auto;
        }

        /* scrollbar */
        #modalShow .table-responsive::-webkit-scrollbar { width: 6px; }
        #modalShow .table-responsive::-webkit-scrollbar-track { background: #1a1a2e; }
        #modalShow .table-responsive::-webkit-scrollbar-thumb { background: #dc3545; border-radius: 3px; }

        #modalShow table.table-history {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        #modalShow table.table-history thead tr {
            background: #1a1a2e;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        #modalShow table.table-history thead th {
            color: #dc3545;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 12px 16px;
            border: none;
            white-space: nowrap;
        }

        #modalShow table.table-history tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: background 0.15s;
        }

        #modalShow table.table-history tbody tr:hover {
            background: rgba(220, 53, 69, 0.08);
        }

        #modalShow table.table-history tbody tr:first-child {
            background: rgba(220, 53, 69, 0.13);
        }

        #modalShow table.table-history tbody tr:first-child:hover {
            background: rgba(220, 53, 69, 0.2);
        }

        #modalShow table.table-history tbody td {
            color: #e0e0e0;
            font-size: 0.88rem;
            padding: 11px 16px;
            border: none;
            vertical-align: middle;
        }

        #modalShow table.table-history tbody tr:first-child td {
            color: #fff;
            font-weight: 600;
        }

        /* rank badge for top bidder */
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 0.7rem;
            font-weight: 700;
            margin-right: 6px;
            flex-shrink: 0;
        }

        .rank-badge.rank-1 {
            background: #dc3545;
            color: #fff;
        }

        .rank-badge.rank-other {
            background: rgba(255,255,255,0.1);
            color: #aaa;
        }

        .badge-auto {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            background: #dc3545;
            color: #fff;
            margin-left: 6px;
            vertical-align: middle;
        }

        /* nominal */
        .nominal-cell {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #f8f9fa;
        }

        #modalShow table.table-history tbody tr:first-child .nominal-cell {
            color: #ff6b7a;
        }

        /* action button */
        .btn-cancel-bid {
            font-size: 0.76rem;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: 600;
        }

        /* empty state */
        .history-empty {
            text-align: center;
            padding: 48px 24px;
            color: #555;
        }

        .history-empty i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
            color: #333;
        }

        /* loading skeleton */
        .history-loading {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 24px 16px;
        }

        .skeleton-row {
            display: flex;
            gap: 12px;
        }

        .skeleton-cell {
            height: 18px;
            border-radius: 4px;
            background: linear-gradient(90deg, #1e1e30 25%, #252538 50%, #1e1e30 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        #modalShow .modal-footer {
            background: #1a1a2e;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        /* Cancel confirm modal */
        #cancelBidConfirm .modal-content {
            border: 1px solid #dc3545;
        }

        #cancelBidConfirm .modal-header {
            background: #dc3545;
            color: #fff;
        }

        #cancelBidConfirm .modal-header .close {
            color: #fff;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Current Auction</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Current Auction</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>No. Ikan</th>
                                                <th>Variety</th>
                                                <th>Breeder</th>
                                                <th>Bloodline</th>
                                                <th>Total Bid</th>
                                                <th>Harga saat ini</th>
                                                <th>Foto</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── History Bidding Modal ── --}}
        <div class="modal fade" id="modalShow" role="dialog" aria-labelledby="modalShowLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content" style="background:#12121f; border:none; border-radius:10px; overflow:hidden;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalShowLabel">
                            <i class="fa fa-history mr-2" style="color:#dc3545;"></i>
                            History Bidding
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalShowBody">
                        {{-- Content injected via JS --}}
                        <div class="history-loading" id="historyLoading">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="skeleton-row">
                                    <div class="skeleton-cell" style="width:30%;"></div>
                                    <div class="skeleton-cell" style="width:25%;"></div>
                                    <div class="skeleton-cell" style="width:20%;"></div>
                                    <div class="skeleton-cell" style="width:15%;"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Cancel Bid Confirm Modal ── --}}
        <div class="modal fade" tabindex="-1" role="dialog" id="cancelBidConfirm">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-exclamation-triangle mr-1"></i>
                            Cancel Bidding
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Anda akan menghapus data bidding ini. Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button onclick="cancelLastBidding(this, 'process')"
                            type="button"
                            class="btn btn-danger"
                            id="yesCancelBidding">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.pages.current-auction._show')
    </div>
@endsection

@push('scripts')
    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/datatables.net-bs4/css/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://demo.getstisla.com/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/js/price-separator.min.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#table-1').DataTable({
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
                buttons: ['pageLength', 'csv', 'excel', 'pdf', 'print'],
                responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                search: { return: true },
                ajax: {
                    url: '{{ url("admin/current-auctions") }}',
                    data: function (d) {}
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'no_ikan' },
                    { data: 'variety' },
                    { data: 'breeder' },
                    { data: 'bloodline' },
                    { data: 'bid_details_count', orderable: false, searchable: false },
                    { data: 'current_price' },
                    { data: 'photo', name: 'photo.path_foto' },
                    { data: 'action', orderable: false, searchable: false },
                ]
            });
        });

        /* ─── Helpers ─── */
        function thousandSeparator(x) {
            if (x === null || x === undefined) return '0';
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function maskName(nama) {
            // Same masking as user side: keep first 2 chars + last 1 char, mask middle
            // Admin can see full name — remove masking below if desired
            return nama;

            // Uncomment below to apply same masking as user-side:
            // return nama.replace(/(.{2})(.+)(.{1})/g, (match, start, middle, end) =>
            //     start + '*'.repeat(middle.length) + end
            // );
        }

        function buildHistoryTable(logBids) {
            if (!logBids || logBids.length === 0) {
                return `<div class="history-empty">
                    <i class="fa fa-inbox"></i>
                    <p class="mb-0">Belum ada data bidding.</p>
                </div>`;
            }

            let rows = '';
            $.each(logBids, function (index, value) {
                const name     = maskName(value.log_bid.member.nama);
                const nominal  = thousandSeparator(value.nominal_bid);
                const isAuto   = value.status_bid === 1;
                const isFirst  = index === 0;

                const rankBadge = isFirst
                    ? `<span class="rank-badge rank-1">1</span>`
                    : `<span class="rank-badge rank-other">${index + 1}</span>`;

                const autoBadge = isAuto
                    ? `<span class="badge-auto">AUTO BID</span>`
                    : '';

                const cancelBtn = isFirst
                    ? `<button class="btn btn-danger btn-sm btn-cancel-bid"
                            data-id="${value.id_bidding_detail}"
                            onclick="cancelLastBidding(this, 'confirm')">
                            <i class="fa fa-times mr-1"></i>Cancel
                        </button>`
                    : `<span class="text-muted" style="font-size:0.75rem;">—</span>`;

                rows += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                ${rankBadge}
                                <span>${name}</span>
                            </div>
                        </td>
                        <td class="nominal-cell">
                            Rp. ${nominal}${autoBadge}
                        </td>
                        <td style="color:#aaa; font-size:0.82rem; white-space:nowrap;">
                            ${value.bid_time}
                        </td>
                        <td>${cancelBtn}</td>
                    </tr>`;
            });

            return `
                <div class="table-responsive">
                    <table class="table-history">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nominal Bidding</th>
                                <th>Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>`;
        }

        /* ─── Show modal ─── */
        $(document).on('click', 'button#btn-show', function () {
            const id = $(this).data('id');

            // Reset to loading state
            $('#modalShowBody').html(`
                <div class="history-loading">
                    ${[...Array(5)].map(() => `
                        <div class="skeleton-row">
                            <div class="skeleton-cell" style="width:30%;"></div>
                            <div class="skeleton-cell" style="width:25%;"></div>
                            <div class="skeleton-cell" style="width:20%;"></div>
                            <div class="skeleton-cell" style="width:15%;"></div>
                        </div>`).join('')}
                </div>
            `);

            $('#modalShow').modal('show');

            $.ajax({
                type: 'GET',
                url: `current-auctions/${id}`,
                dataType: 'json',
                success: function (res) {
                    $('#modalShowBody').html(buildHistoryTable(res.log_bids));
                },
                error: function (err) {
                    $('#modalShowBody').html(`
                        <div class="history-empty">
                            <i class="fa fa-exclamation-circle" style="color:#dc3545;"></i>
                            <p class="mb-0 text-danger">Gagal memuat data. Silakan coba lagi.</p>
                        </div>
                    `);
                    console.error(err);
                }
            });
        });

        /* ─── Cancel bidding ─── */
        function cancelLastBidding(e, type) {
            const id = $(e).attr('data-id');

            if (type === 'confirm') {
                $('#cancelBidConfirm').modal('show');
                $('#yesCancelBidding').attr('data-id', id);
                return;
            }

            if (type === 'process') {
                $('#yesCancelBidding').addClass('disabled').prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin mr-1"></i>Memproses...'
                );

                $.ajax({
                    type: 'PATCH',
                    contentType: false,
                    processData: false,
                    url: `current-auctions/${id}`,
                    success: function (res) {
                        if (res.success) {
                            $('#cancelBidConfirm').modal('hide');
                            $('#table-1').DataTable().ajax.reload(null, false);
                            swal(res.message.title, res.message.content, res.message.type);

                            // Refresh history in modal if still open
                            const $showModal = $('#modalShow');
                            if ($showModal.hasClass('show') || $showModal.is(':visible')) {
                                // Re-fetch to refresh history — get fish id from cancel button's context
                                // Trigger a re-fetch on the last known id
                                const fishId = $('#yesCancelBidding').data('fish-id');
                                if (fishId) {
                                    $.get(`current-auctions/${fishId}`, function (res2) {
                                        $('#modalShowBody').html(buildHistoryTable(res2.log_bids));
                                    });
                                } else {
                                    $showModal.modal('hide');
                                }
                            }
                        }
                    },
                    error: function (err) {
                        swal('Error', 'Gagal membatalkan bidding. Silakan coba lagi.', 'error');
                        console.error(err);
                    },
                    complete: function () {
                        $('#yesCancelBidding')
                            .removeClass('disabled')
                            .prop('disabled', false)
                            .html('Ya, Hapus');
                    }
                });
            }
        }

        // Track current fish id for post-cancel refresh
        $(document).on('click', 'button#btn-show', function () {
            $('#yesCancelBidding').data('fish-id', $(this).data('id'));
        });

        /* ─── Edit modal (existing, untouched) ─── */
        $(document).on('click', 'button#btn-edit', function () {
            const id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: `auction-products/${id}`,
                dataType: 'json',
                success: function (res) {
                    document.getElementById('formEdit').action = `auction-products/${id}`;
                    $('#modalEdit').modal('show');
                    $('#edit_no_ikan').val(res.no_ikan);
                    $('#edit_variety').val(res.variety);
                    $('#edit_breeder').val(res.breeder);
                    $('#edit_bloodline').val(res.bloodline);
                    $('#edit_sex').html(`
                        <option value="Male"    ${res.sex === 'Male'    ? 'selected' : ''}>Male</option>
                        <option value="Female"  ${res.sex === 'Female'  ? 'selected' : ''}>Female</option>
                        <option value="Unknown" ${res.sex === 'Unknown' ? 'selected' : ''}>Unknown</option>
                    `);
                    $('#edit_dob').val(res.dob);
                    $('#edit_size').val(res.size);
                    $('#edit_ob').val(res.ob);
                    $('#edit_kb').val(res.kb);
                    $('#edit_ob').priceFormat({ prefix: '', centsLimit: 0, thousandsSeparator: '.' });
                    $('#edit_kb').priceFormat({ prefix: '', centsLimit: 0, thousandsSeparator: '.' });
                    tinymce.get('edit_note').setContent(res.note);
                    $('#edit_link_video').val(res.link_video);
                    $('#edit_extra_time').val(res.extra_time);
                    $('#edit_foto2').attr('src', res.photo?.path_foto ? `/storage/${res.photo.path_foto}` : '');
                },
                error: function (err) { console.log(err); }
            });
        });

        $('#formEdit').submit(function (e) {
            e.preventDefault();
            tinymce.triggerSave();
            let formData = new FormData(this);
            formData.append('_method', 'PATCH');

            const fields = ['no_ikan','variety','breeder','bloodline','sex','dob','size','ob','kb','note','link_video','extra_time'];
            fields.forEach(f => {
                formData.append(f, formData.get(`edit_${f}`));
                formData.delete(`edit_${f}`);
            });
            formData.append('path_foto', formData.get('edit_foto'));
            formData.delete('edit_foto');

            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                url: $(this).attr('action'),
                beforeSend: function () {
                    $('#btn-update').addClass('btn-progress');
                    $(document).find('span.error-text').text('');
                },
                complete: function () {
                    $('#btn-update').removeClass('btn-progress');
                },
                success: function (res) {
                    if (res.success) {
                        location.reload();
                        $('#modalEdit').modal('hide');
                        $('#table-1').DataTable().ajax.reload();
                        swal(res.message.title, res.message.content, res.message.type);
                    }
                },
                error: function (err) {
                    $.each(err.responseJSON, function (prefix, val) {
                        $(`.${prefix}_error_edit`).text(val[0]);
                    });
                }
            });
        });
    </script>
@endpush