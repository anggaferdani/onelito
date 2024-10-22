@component('mail::message')
Terima kasih telah melakukan registrasi.

@component('mail::button', ['url' => $url])
Click untuk verifikasi
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
