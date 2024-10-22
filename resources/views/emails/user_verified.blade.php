@component('mail::message')
Halo Onelito Admin,

Akun pengguna baru telah berhasil diverifikasi:

<p>Pengguna: {{ $user->nama }}</p>
<p>Email: {{ $user->email }}</p>
<p>Tanggal Verifikasi: {{ $user->email_verified_at }}</p>

<!-- <p>Mohon untuk segera mengaktivasi akun pengguna ini.</p> -->
<!-- <p>Anda dapat melakukan aktivasi melalui dashboard admin menu Member.</p> -->

<br>
{{ config('app.name') }}
@endcomponent
