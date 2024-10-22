<button class="btn btn-sm btn-secondary mb-2"
data-peserta="{{ $model->id_peserta }}"
data-event="{{ $model->id_event }}"
id="btn-show"
data-toggle="tooltip"
data-placement="top"
title="Lihat Detail">
    <i class="fa fa-eye"></i>
</button>
@if($model->status_pembayaran === 0)
<button class="btn btn-sm btn-success mb-2"
data-peserta="{{ $model->id_peserta }}"
data-event="{{ $model->id_event }}"
id="btn-completed"
data-toggle="tooltip"
data-status="{{ $model->status_pembayaran }}"
data-placement="top"
title="Edit">
    <i class="fa fa-check"></i>
</button>
@else
<button class="btn btn-sm btn-warning mb-2"
data-peserta="{{ $model->id_peserta }}"
data-event="{{ $model->id_event }}"
id="btn-completed"
data-toggle="tooltip"
data-status="{{ $model->status_pembayaran }}"
data-placement="top"
title="Edit">
    <i class="fa fa-pencil"></i>
</button>
@endif


<!-- <button class="btn btn-sm btn-danger mb-2"
    id="btn-delete"
    data-url="{{ url('admin/auction-winners/'. $model->id_pemenang_lelang) }}"
    data-id="{{ $model->id_pemenang_lelang }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Hapus Pemenang Lelang">

    <i class="fa fa-trash"></i>
</button> -->

<script>
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
</script>
