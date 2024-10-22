@component('mail::message')
    Hi Admin Onelito,


    Request order :

        No Pembelian: {{ $order->no_order }}
        Nama: {{ $order->latestDetail->member->nama }}


    Silahkan mengecek orderan melalui aplikasi admin.
@endcomponent
