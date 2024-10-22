@extends('layout.mainlog')

@section('container')
<div class="container">
    <a href="/auctionlog"><i class="fa-solid fa-arrow-left-long text-body" style="font-size: x-large"></i></a>

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
                <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                <br><br>
            </div>
            <div class="card-body p-0">
                <a href="#" class="btn btn-danger w-100 d-flex justify-content-between" style="font-size:larger">VIDEO <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
            </div>
        </div>
        <div class="col-6 ps-0">
            <p class="m-0" style="font-size: 11px">Auction Detail</p>
            <hr class="m-0">
            <h3 style="font-size: 12px"><table>
                <tr>
                    <td>Variety</td>
                    <td>: Kohaku</td>
                </tr>
                <tr>
                    <td>Breeder</td>
                    <td>: Sakai Fish Farm</td>
                </tr>
                <tr>
                    <td>Bloodline</td>
                    <td>: S Legend</td>
                </tr>
                <tr>
                    <td>Sex</td>
                    <td>: Female</td>
                </tr>
                <tr>
                    <td>DOB</td>
                    <td>: 2020</td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td>: 57 cm</td>
                </tr>
            </table></h3>

            <hr class="m-0">

            <p class="m-0" style="font-size: 11px">Note :</p>
            <p style="font-size: 10px">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum, voluptas! Porro suscipit obcaecati eius quia qui dolorem harum ipsam, illo laudantium officiis maiores commodi aliquid fugiat, laboriosam ipsa similique blanditiis.</p>

            <hr class="m-0">

            <p class="m-0" style="font-size:11px">Harga saat ini: <span class="alert-link text-danger" style="font-size:12px">Rp 6.500.000</span></p>

            <hr class="m-0">

            <p style="font-size:10px" class="m-0">Kelipatan BID: <span class="alert-link text-danger" style="font-size: 11px">Rp 100.000</span></p>

            <hr class="m-0">

            <p class="m-0 mt-2" style="font-size: 13px">Countdown</p>
            <p class="alert-link text-danger" style="font-size: 16px">00 : 35 : 45</p>
        </div>

        <div class="input-group input-group-sm mb-3 mt-5">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Nominal BID">
            <span class="bg-danger input-group-text text-white" id="inputGroup-sizing-sm">BID AUCTION</span>
        </div>
        <div class="input-group input-group-sm mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Nominal Max Auto BID">
            <span class="bg-danger input-group-text text-white" id="inputGroup-sizing-sm">AUTO BID</span>
        </div>
    </div>
</div>

<div class="web">
    <div class="row gx-5">
        <div class="col-4">
                <div class="m-lg-auto" style="width: 18rem;">
                    <img src="img/koi_3.jpg" class="card-img-top" alt="...">
                    <br><br>
                    <div class="card-body p-0">
                        <a href="#" class="btn btn-danger w-100 d-flex justify-content-between" style="font-size:larger">VIDEO <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                    </div>
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
                        <td>: Kohaku</td>
                    </tr>
                    <tr>
                        <td>Breeder</td>
                        <td>: Sakai Fish Farm</td>
                    </tr>
                    <tr>
                        <td>Bloodline</td>
                        <td>: S Legend</td>
                    </tr>
                </table></h3>
                </div>
                <div class="col">
                <h3><table>
                    <tr>
                        <td>Sex</td>
                        <td>: Female</td>
                    </tr>
                    <tr>
                        <td>DOB</td>
                        <td>: 2020</td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <td>: 57 cm</td>
                    </tr>
                </table></h3>
                </div>
            </div>
            <hr>

            <p class="m-0" style="font-size: larger">Note :</p>
            <p style="font-size: larger">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum, voluptas! Porro suscipit obcaecati eius quia qui dolorem harum ipsam, illo laudantium officiis maiores commodi aliquid fugiat, laboriosam ipsa similique blanditiis.</p>
            <hr>

            <p style="font-size:30px">Harga saat ini: <span class="alert-link text-danger">Rp 6.500.000</span></p>
            <hr>

            <p style="font-size:25px">Kelipatan BID: <span class="alert-link text-danger">Rp 100.000</span></p>
            <hr>

            <p class="m-0" style="font-size: larger">Countdown</p>
            <p class="alert-link text-danger" style="font-size: 30px">00 : 35 : 45</p>
            <br><br>

            <form class="row">
                <div class="col-9" style="padding-right: 0;">
                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Nominal BID">
                </div>
                <div class="col-3" style="padding-left: 0">
                    <button type="submit" class="btn btn-danger mb-3 w-100 justify-content-between">BID AUCTION</button>
                </div>
            </form>

            <form class="row">
                <div class="col-9" style="padding-right: 0;">
                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Nominal Max Auto BID">
                </div>
                <div class="col-3" style="padding-left: 0">
                    <button type="submit" class="btn btn-danger mb-3 w-100 justify-content-between">AUTO BID</button>
                </div>
            </form>
        </div>
    </div>
</div>

<br><br><br>
</div>
@endsection
