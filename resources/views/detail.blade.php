@extends('layout.mainlog')

@section('container')
<div class="container">
    <a href="/auction"><i class="fa-solid fa-arrow-left-long text-body" style="font-size: x-large"></i></a>

    <style>

        /* On screens that are 992px or less, set the background color to blue */
        @media screen and (min-width: 601px) {
        .res {
            display: none
            }
        }

        /* On screens that are 600px or less, set the background color to olive */
        @media screen and (max-width: 600px) {
        .web {
            display: none;
            }
        }

    </style>

    <div class="res">
        <div class="row">
            <div class="col-6">
                <div class="">
                    @php
                        $imgUrl = 'img/koi12.jpg';

                        if ($auctionProduct->photo) {
                            $imgUrl = 'storage/'. $auctionProduct->photo->path_foto;
                        }

                    @endphp
                    <img src="{{ url($imgUrl) }}" class="card-img-top" alt="...">
                    <br><br>
                </div>
                <div>
                    <a class="btn btn-danger mb-3 d-block" href="/bid" style="font-size: 12px" role="button">BID Now</a>
                </div>
            </div>
            <div class="col-6 ps-0">
                <p class="m-0" style="font-size: 11px">Auction Detail</p>
                <hr class="m-0">
                <h3><table>
                    <tr>
                        <td>Variety</td>
                        <!-- <td>: Kohaku</td> -->
                        <td>: {{ $auctionProduct->variety }}</td>
                    </tr>
                    <tr>
                        <td>Breeder</td>
                        <!-- <td>: Sakai Fish Farm</td> -->
                        <td>: {{ $auctionProduct->breeder }}</td>
                    </tr>
                    <tr>
                        <td>Bloodline</td>
                        <!-- <td>: S Legend</td> -->
                        <td>: {{ $auctionProduct->bloodline }}</td>
                    </tr>
                </table></h3>
                </div>
                <div class="col">
                <h3><table>
                    <tr>
                        <td>Sex</td>
                        <!-- <td>: Female</td> -->
                        <td>: {{ $auctionProduct->sex }}</td>
                    </tr>
                    <tr>
                        <td>DOB</td>
                        <!-- <td>: 2020</td> -->
                        <td>: {{ $auctionProduct->dob }}</td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <!-- <td>: 57 cm</td> -->
                        <td>: {{ $auctionProduct->size }}</td>
                    </tr>
                </table></h3>

                <hr class="m-0">

                <p class="m-0" style="font-size: 11px">Note :</p>
                <p style="font-size: 10px">{{ $auctionProduct->note }}</p>

                <hr class="m-0">

                @php
                    $currentPrice = $auctionProduct->ob;

                    $currentPrice = $maxBid > $currentPrice ? $maxBid : $currentPrice;
                @endphp

                <p class="m-0" style="font-size:11px">Harga saat ini: <span class="alert-link text-danger" style="font-size:12px">Rp {{ $currentPrice }}</span></p>

                <hr class="m-0">

                <p style="font-size:10px" class="m-0">Kelipatan BID: <span class="alert-link text-danger" style="font-size: 11px">Rp. {{ $auctionProduct->kb }}</span></p>

                <hr class="m-0">

                <p class="m-0 mt-2" style="font-size: 13px">Countdown</p>
                <p class="alert-link text-danger" style="font-size: 16px">00 : 35 : 45</p>
            </div>
        </div>
    </div>

    <div class="web">
        <div class="row gx-5">
            <div class="col-4">
                <div class="m-lg-auto" style="width: 18rem;">
                    @php
                        $imgUrl = 'img/koi_3.jpg';

                        if ($auctionProduct->photo) {
                            $imgUrl = 'storage/'. $auctionProduct->photo->path_foto;
                        }

                    @endphp
                    <img src="{{ url($imgUrl) }}" class="card-img-top" alt="...">
                    <br><br>
                </div>
            </div>
            <div class="col-8">
                <p style="font-size: larger">Auction Detail</p>
                <hr>

            <div class="row">
                <div class="col">
                <h3><table>
                    <tr>
                        <td>Variety</td>
                        <!-- <td>: Kohaku</td> -->
                        <td>: {{ $auctionProduct->variety }}</td>
                    </tr>
                    <tr>
                        <td>Breeder</td>
                        <!-- <td>: Sakai Fish Farm</td> -->
                        <td>: {{ $auctionProduct->breeder }}</td>
                    </tr>
                    <tr>
                        <td>Bloodline</td>
                        <!-- <td>: S Legend</td> -->
                        <td>: {{ $auctionProduct->bloodline }}</td>
                    </tr>
                </table></h3>
                </div>
                <div class="col">
                <h3><table>
                    <tr>
                        <td>Sex</td>
                        <!-- <td>: Female</td> -->
                        <td>: {{ $auctionProduct->sex }}</td>
                    </tr>
                    <tr>
                        <td>DOB</td>
                        <!-- <td>: 2020</td> -->
                        <td>: {{ $auctionProduct->dob }}</td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <!-- <td>: 57 cm</td> -->
                        <td>: {{ $auctionProduct->size }}</td>
                    </tr>
                </table></h3>
                </div>
            </div>

                <hr>

                <p class="m-0" style="font-size: larger">Note :</p>
                <p style="font-size: larger">{{ $auctionProduct->note }}</p>
                <hr>

                <p style="font-size:30px">Harga saat ini: <span class="alert-link text-danger">Rp {{ $currentPrice }}</span></p>
                <hr>

                <p style="font-size:25px">Kelipatan BID: <span class="alert-link text-danger">Rp. {{ $auctionProduct->kb }}</span></p>
                <hr>

                <p class="m-0" style="font-size: larger">Countdown</p>
                <p class="alert-link text-danger" style="font-size: 30px">00 : 35 : 45</p>
                <br><br>
                <div class="col-3">
                    <a class="btn btn-danger mb-3" href="{{ url('auction') . '/' . $idIkan }}" role="button">BID Now</a>
                </div>
            </div>
        </div>
    </div>

    <br><br><br><br><br><br>
</div>
@endsection
