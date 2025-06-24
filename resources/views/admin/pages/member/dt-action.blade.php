<button class="btn btn-sm btn-secondary mb-2"
data-url="{{ url('admin/members/'. $model->id_peserta) }}"
data-id="{{ $model->id_peserta }}"
id="btn-show"
data-toggle="tooltip"
data-placement="top"
title="Lihat Detail">
    <i class="fa fa-eye"></i>
</button>

<button class="btn btn-sm btn-warning mb-2"
data-url="{{ url('admin/members/'. $model->id_peserta) }}"
data-id="{{ $model->id_peserta }}"
id="btn-edit"
data-toggle="tooltip"
data-placement="top"
title="Edit">
    <i class="fa fa-pencil"></i>
</button>

@if ($model->status_phone_number_verification == 0)
<button class="btn btn-sm btn-success mb-2"
    id="btn-send-otp"
    data-url="{{ url('/request-verification-otp/'. $model->verification_token) }}"
    data-id="{{ $model->id_peserta }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Send OTP WhatsApp Verification">

    <i class="fab fa-whatsapp"></i>
</button>

{{-- <button class="btn btn-sm btn-primary mb-2"
    id="btn-copy-url-verif"
    data-url="{{ url('/send-email/'. $model->email) }}"
    data-id="{{ $model->id_peserta }}"
    data-email-url="{{ $url }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Copy url Email Verification">

    <i class="fa fa-clipboard"></i>
</button> --}}
@endif

<button class="btn btn-sm btn-danger mb-2"
    id="btn-password"
    data-url="{{ url('admin/members/'. $model->id_peserta) }}"
    data-id="{{ $model->id_peserta }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Reset Password">

    <i class="fa fa-lock"></i>
</button>

<button class="btn btn-sm btn-danger mb-2"
    id="btn-delete"
    data-url="{{ url('admin/members/'. $model->id_peserta) }}"
    data-id="{{ $model->id_peserta }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Hapus Peserta">

    <i class="fa fa-trash"></i>
</button>

<script>
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
</script>
