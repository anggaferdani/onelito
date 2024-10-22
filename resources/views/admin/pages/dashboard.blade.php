@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/flag-icon-css/css/flag-icon.min.css') }}">
@endpush

@section('main')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Event Auction</h4>
                            </div>
                            <div class="card-body">
                                {{ $countEventAuction }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Regular Auction</h4>
                            </div>
                            <div class="card-body">
                                {{ $countRegularAuction }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Product Store</h4>
                            </div>
                            <div class="card-body">
                                {{ $countProduct }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Product Store Terjual</h4>
                            </div>
                            <div class="card-body">
                                {{ $countProductSold }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <!-- <div class="row"> -->
                                <h4>Jumlah Penjualan Produk Store</h4>
                            <!-- </div> -->
                            <!-- <div class="row">/ -->
                                <div class="col-md-3">
                                    <!-- <label for=""><b>Bulan</b></label> -->
                                    <select name="my_chart_month" class="form-control select2" id="my_chart_month">
                                        @foreach ($months as $key => $month)
                                            @if ($key === $now->month)
                                                <option selected value="{{ $key }}">{{ $month }}</option>

                                            @else
                                                <option value="{{ $key }}">{{ $month }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            <!-- </div> -->
                        </div>
                        <div class="card-body">
                            <canvas height="300" id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4>Nominal Penjualan Produk Store</h4>

                            <div class="col-md-3">
                                    <!-- <label for=""><b>Bulan</b></label> -->
                                    <select name="my_chart_month2" class="form-control select2" id="my_chart_month2">
                                        @foreach ($months as $key => $month)
                                            @if ($key === $now->month)
                                                <option selected value="{{ $key }}">{{ $month }}</option>

                                            @else
                                                <option value="{{ $key }}">{{ $month }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="card-body">
                            <canvas height="300" id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4>Grafik Peserta Auction</h4>

                            <div class="col-md-3">
                                    <select name="my_chart_month3" class="form-control select2" id="my_chart_month3">
                                        @foreach ($months as $key => $month)
                                            @if ($key === $now->month)
                                                <option selected value="{{ $key }}">{{ $month }}</option>

                                            @else
                                                <option value="{{ $key }}">{{ $month }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="card-body">
                            <canvas height="300" id="myChart3"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <!-- <script src="{{ asset('js/page/index.js') }}"></script> -->

    <script>

        // console.log(@json($thisMonthsProductSoldCharts['labels']))
        var ctx = document.getElementById("myChart").getContext('2d');
        var productSoldChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($thisMonthsProductSoldCharts['labels']),
                datasets: [{
                    data: @json($thisMonthsProductSoldCharts['data']),
                    borderWidth: 2,
                    backgroundColor: '#6777ef',
                    borderColor: '#6777ef',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        color: '#f2f2f2',
                    },
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    ticks: {
                        // Include a dollar sign in the ticks
                        // callback: function(value, index, ticks) {
                        //     return 'Tanggal' + value;
                        // }
                    },
                    gridLines: {
                    display: false
                    }
                }]
                },
                tooltips: {
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal ' + context[0].label;
                        }
                    }
                },
            }
        });

        var ctx2 = document.getElementById("myChart2").getContext('2d');
        var productSoldChartNominal = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: @json($thisMonthsNominalProductSoldCharts['labels']),
                datasets: [{
                    data: @json($thisMonthsNominalProductSoldCharts['data']),
                    borderWidth: 2,
                    backgroundColor: '#6777ef',
                    borderColor: '#6777ef',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }]
            },
            options: {
                // responsive: true,
                maintainAspectRatio: false,
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        color: '#f2f2f2',
                    },
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    ticks: {
                        // Include a dollar sign in the ticks
                        // callback: function(value, index, ticks) {
                        //     return 'Tanggal' + value;
                        // }
                    },
                    gridLines: {
                    display: false
                    }
                }]
                },
                tooltips: {
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal ' + context[0].label;
                        }
                    }
                },
            }
        });

        var ctx3 = document.getElementById("myChart3").getContext('2d');
        var auctionParticipantChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Regular',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: '#6777ef',
                    borderColor: '#6777ef',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                },{
                    label: 'Event',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: '#fc5603',
                    borderColor: '#fc5603',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fc5603',
                    pointRadius: 4
                }]
            },
            options: {
                // responsive: true,
                maintainAspectRatio: false,
                legend: {
                // display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        color: '#f2f2f2',
                    },
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    ticks: {
                        // Include a dollar sign in the ticks
                        // callback: function(value, index, ticks) {
                        //     return 'Tanggal' + value;
                        // }
                    },
                    gridLines: {
                    display: false
                    }
                }]
                },
                tooltips: {
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal ' + context[0].label;
                        }
                    }
                },
            }
        });

        $('#my_chart_month').change(function(e){
            var select = $("#my_chart_month").val();
            $.ajax({
                url : '{{ url("admin/charts/sum-product-sold") }}?month='+select,
                type : 'GET',
                data : {
                },

                beforeSend : function() {
                    productSoldChart.clicked = false;
                },
                success : function(res) {
                    productSoldChart.data.datasets[0].data = res.data;
                    productSoldChart.data.labels = res.labels
                    productSoldChart.update();
                    productSoldChart.clicked = true;
                },
                error : function(err) {
                console.log(err);
                }
            })
        });

        $('#my_chart_month2').change(function(e){
            var select = $("#my_chart_month2").val();
            $.ajax({
                url : '{{ url("admin/charts/sum-nominal-product-sold") }}?month='+select,
                type : 'GET',
                data : {
                },

                beforeSend : function() {
                    productSoldChartNominal.clicked = false;
                },
                success : function(res) {
                    productSoldChartNominal.data.datasets[0].data = res.data;
                    productSoldChartNominal.data.labels = res.labels
                    productSoldChartNominal.update();
                    productSoldChartNominal.clicked = true;
                },
                error : function(err) {
                console.log(err);
                }
            })
        });

        $('#my_chart_month3').change(function(e){
            chartSumAuctionParticipant()
            // var select = $("#my_chart_month3").val();
            // $.ajax({
            //     url : '{{ url("admin/charts/sum-auction-participant") }}?month='+select,
            //     type : 'GET',
            //     data : {
            //     },

            //     beforeSend : function() {
            //         auctionParticipantChart.clicked = false;
            //     },
            //     success : function(res) {
            //         auctionParticipantChart.data.datasets[0].data = res.regular.data;
            //         auctionParticipantChart.data.datasets[1].data = res.special.data;
            //         auctionParticipantChart.data.labels = res.regular.labels
            //         auctionParticipantChart.update();
            //         auctionParticipantChart.clicked = true;
            //     },
            //     error : function(err) {
            //         console.log(err);
            //     }
            // })
        });

        async function chartSumAuctionParticipant()
        {
            var select = $("#my_chart_month3").val();
            $.ajax({
                url : '{{ url("admin/charts/sum-auction-participant") }}?month='+select,
                type : 'GET',
                data : {
                },

                beforeSend : function() {
                    auctionParticipantChart.clicked = false;
                },
                success : function(res) {
                    auctionParticipantChart.data.datasets[0].data = res.regular.data;
                    auctionParticipantChart.data.datasets[1].data = res.special.data;
                    auctionParticipantChart.data.labels = res.regular.labels
                    auctionParticipantChart.update();
                    auctionParticipantChart.clicked = true;
                },
                error : function(err) {
                    console.log(err);
                }
            })
        }

        chartSumAuctionParticipant();

    </script>
@endpush
