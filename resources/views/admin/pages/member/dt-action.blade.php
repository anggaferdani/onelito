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

@if ($model->email_verified_at === null)

@php
    $payload = array(
                        'id'        => $model->id_peserta,
                        'email'     => $model->email,
                        'action'       => 'email-verification',
                    );

    $crypt = Crypt::encrypt($payload);

    $url = config('app.url') . "/ls/click?click=$crypt";
@endphp

{{-- <button class="btn btn-sm btn-primary mb-2"
    id="btn-send-email"
    data-url="{{ url('/send-email/'. $model->email) }}"
    data-id="{{ $model->id_peserta }}"
    data-toggle="tooltip"
    data-placement="top"
    title="Send Email Verify">

    <i class="fa fa-envelope"></i>
</button>

<button class="btn btn-sm btn-primary mb-2"
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
