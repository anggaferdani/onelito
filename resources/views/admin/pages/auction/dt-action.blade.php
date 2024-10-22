
<button class="btn btn-sm btn-secondary mb-2"
data-url="{{ url('admin/auction-products/'. $model->id_event) }}"
data-id="{{ $model->id_event }}"
id="btn-show"
data-toggle="tooltip"
data-placement="top"
title="Lihat Detail">
    <i class="fa fa-eye"></i>
</button>

<button class="btn btn-sm btn-primary mb-2"
data-url="{{ url('admin/auction-products/'. $model->id_event) }}"
data-id="{{ $model->id_event }}"
id="btn-close"
data-toggle="tooltip"
data-placement="top"
title="Tutup Event">
    <i class="fa fa-stop"></i>
</button>

<button class="btn btn-sm btn-warning mb-2"
data-url="{{ url('admin/auction-products/'. $model->id_event) }}"
data-id="{{ $model->id_event }}"
id="btn-edit"
data-toggle="tooltip"
data-placement="top"
title="Edit">
    <i class="fa fa-pencil"></i>
</button>


<button class="btn btn-sm btn-danger mb-2"
    id="btn-delete"
    data-url="{{ url('admin/auction-products/'. $model->id_event) }}"
    data-id="{{ $model->id_event }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Hapus Auction">

    <i class="fa fa-trash"></i>
</button>

<script>
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
</script>
