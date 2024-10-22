<button class="btn btn-sm btn-secondary mb-2"
data-url="{{ url('admin/auction-products/'. $model->id_ikan) }}"
data-id="{{ $model->id_ikan }}"
id="btn-show"
data-toggle="tooltip"
data-placement="top"
title="Lihat Detail">
    <i class="fa fa-eye"></i>
</button>

<script>
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
</script>
