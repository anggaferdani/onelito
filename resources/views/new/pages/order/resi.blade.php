<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Order Label</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        .row {
            margin: 0;
        }

        .barcode-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
            flex-direction: column;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            padding: 0.2rem !important;
            font-size: 0.9em;
        }

        .fs-3 {
            font-size: 1.1rem !important;
        }

        .fs-5 {
            font-size: 0.9rem !important;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                min-height: 100vh !important;
                margin: 0 !important;
                padding: 0 !important;
                font-size: 12px !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .table {
                margin-bottom: 0 !important;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important;
                padding: 0.1rem !important;
                font-size: 0.8em !important;
                line-height: 1.1 !important;
            }

            table, tr, td, tbody, thead, div {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            img {
                max-width: 100% !important;
                height: auto !important;
            }

            .no-print {
                display: none !important;
            }
        }

        .img-fluid {
            max-width: 70%;
            height: auto;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="row py-5">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2">
                        <div class="text-center">
                            @if($logoSrc)
                                <img src="{{ $logoSrc }}" class="img-fluid" alt="Logo">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="barcode-container text-center mb-1">
                            <div style="display: inline-block;">  <!-- Add this wrapper -->
                                {!! $waybillBarcode !!}
                            </div>
                        </div>
                        <div class="fs-5 text-center">Nomor Resi - {{ $waybillIdFormatted ?? $waybillId }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="fs-3 text-center">Ongkos Kirim : {{ $order->courier_price }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="fs-3 text-center">Jenis Layanan - {{ $order->courier_service_name }}</div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <div class="fs-5 text-center fw-bold mb-1">Reference Number</div>
                        <div class="barcode-container mb-1">
                            {!! $referenceNumberBarcode !!}
                        </div>
                        <div class="fs-5 text-center">{{ $orderIdFormatted ?? $orderId }}</div>
                    </td>
                    <td class="text-center align-middle">
                        <div class="">Quantity : {{ $totalQuantity }} Pcs</div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <div class="">Weight : {{ $totalWeight }} Kg</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <div class="fs-5 fw-bold">Alamat Penerima :</div>
                        <div class="fs-5">{{ $order->destination_contact_name }}</div>
                        <div class="fs-5">{{ $order->destination_contact_phone }}</div>
                        <div class="fs-5">{{ $order->destination_address }}, {{ $order->origin_postal_code }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="fs-5 fw-bold">Alamat Pengirim :</div>
                        <div class="fs-5">{{ $order->origin_contact_name }}</div>
                        <div class="fs-5">{{ $order->origin_contact_phone }}</div>
                        <div class="fs-5">{{ $order->origin_address }}, {{ $order->origin_postal_code }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-3">
                                <div class="fs-5 fw-bold">Jenis Barang :</div>
                            </div>
                            <div class="col-9">
                                @foreach($order->details as $detail)
                                    <div class="fs-5">{{ $detail->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-3">
                                <div class="fs-5 fw-bold">Catatan :</div>
                            </div>
                            <div class="col-9">
                                <div class="fs-5">{{ $order->order_note ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="fs-5 text-center text-muted">www.onelitokoi.id</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>