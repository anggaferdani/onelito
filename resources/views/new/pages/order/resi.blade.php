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
        }

        .barcode-container canvas {
            max-width: 100%;
            height: auto;
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

        /* Gaya khusus untuk cetak */
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

            /* Hindari page break di dalam elemen penting */
            table, tr, td, tbody, thead, div {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            /* Paksa gambar agar tidak melebihi batas */
            img {
                max-width: 100% !important;
                height: auto !important;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Gaya untuk logo */
        .img-fluid {
            max-width: 70%;
            height: auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>

</head>
<body>
@php
    $qrCodeValue = $order->waybill_id;
    $referenceNumberValue = $order->order_id;

    // Calculate total quantity and weight
    $totalQuantity = 0;
    $totalWeight = 0;
    foreach($order->details as $detail) {
        $totalQuantity += $detail->quantity;

        // Convert weight to kilograms
        $weightInKilograms = $detail->weight / 1000;  // Assuming $detail->weight is in grams

        $totalWeight += $weightInKilograms * $detail->quantity; // Weight of item multiplied by quantity
    }

    $totalWeight = number_format($totalWeight, 2); // Format to 2 decimal places
@endphp
<div class="container">
    <div class="row py-5">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2">
                        <div class="text-center"><img src="{{ asset('img/logo-onelito.png') }}" class="img-fluid" ></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="barcode-container">
                            <canvas id="qrCodeCanvas" width="200" height="50"></canvas>
                            <script>
                                JsBarcode("#qrCodeCanvas", "{{ $qrCodeValue }}", {format: "CODE128"});
                            </script>
                        </div>
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
                        <div class="fs-5 text-center fw-bold">Reference Number</div>
                        <div class="barcode-container">
                            <canvas id="referenceNumberCanvas" width="200" height="50"></canvas>
                            <script>
                                JsBarcode("#referenceNumberCanvas", "{{ $referenceNumberValue }}", {format: "CODE128"});
                            </script>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center align-items-center fs-3">
                            <div class="">Quantity</div>
                            <div class="mx-5">:</div>
                            <div class="">{{ $totalQuantity }} Pcs</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center align-items-center fs-3">
                            <div class="">Weight</div>
                            <div class="mx-5">:</div>
                            <div class="">{{ $totalWeight }} Kg</div>
                        </div>
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