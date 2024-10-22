@component('mail::message')
Kami menerima permintaan untuk mengubah password Anda. Anda dapat mengubah password anda dengan menekan tombol berikut:

@component('mail::button', ['url' => $url])
Click Untuk Reset Password
@endcomponent

Atau gunakan link berikut:

<a target="_blank" href="{{ $url }}">Link Reset Password</a>

Apabila Anda merasa tidak sedang meminta perubahan password, Anda dapat mengabaikan pesan ini dan tetap lanjut menggunakan password Anda saat ini.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
