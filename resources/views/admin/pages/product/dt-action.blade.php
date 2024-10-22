<button class="btn btn-sm btn-secondary mb-2"
data-url="{{ url('admin/products/'. $model->id_produk) }}"
data-id="{{ $model->id_produk }}"
id="btn-show"
data-toggle="tooltip"
data-placement="top"
title="Lihat Detail">
    <i class="fa fa-eye"></i>
</button>

<button class="btn btn-sm btn-warning mb-2"
data-url="{{ url('admin/products/'. $model->id_produk) }}"
data-id="{{ $model->id_produk }}"
id="btn-edit"
data-toggle="tooltip"
data-placement="top"
title="Edit">
    <i class="fa fa-pencil"></i>
</button>


<button class="btn btn-sm btn-danger mb-2"
    id="btn-delete"
    data-url="{{ url('admin/products/'. $model->id_produk) }}"
    data-id="{{ $model->id_produk }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Hapus Barang Store">

    <i class="fa fa-trash"></i>
</button>

<script>
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
</script>
