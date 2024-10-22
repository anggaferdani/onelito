@extends('layout.main')

@section('container')
    <br><br><br><br>
    <br>

    @php
    @endphp

    <div class="container">
        <a href="/koi_stok"><i class="fa-solid fa-arrow-left-long text-body" style="font-size: x-large"></i></a>

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

    @php
        $photo = url('/img/koi12.jpg');
        if ($fish->foto_ikan !== null) {
                $photo = url('storage') . '/' . $fish->foto_ikan;
        }
    @endphp

        <div class="res">
            <div class="row">
                <div class="col-6">
                    <div class="">

                        <img src="{{ $photo }}" class="card-img-top" alt="...">
                        <br>
                    </div>
                    <div>
                        <a class="btn btn-danger mb-3 d-block d-flex justify-content-between mt-2"
                            target="_blank" href="{{ $fish->link_video }}"
                            style="font-size: 12px" role="button">VIDEO
                            <span><i class="fa-solid fa-circle-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-6 ps-0">
                    <p class="m-0" style="font-size: 11px">Detail Koi</p>
                    <hr class="m-0">
                    <h3 style="font-size: 12px">
                        <table>
                            <tr>
                                <td>Variety</td>
                                <td>: {{ $fish->variety }}</td>
                            </tr>
                            <tr>
                                <td>Breeder</td>
                                <td>: {{ $fish->breeder }}</td>
                            </tr>
                            <tr>
                                <td>Bloodline</td>
                                <td>: {{ $fish->bloodline }}</td>
                            </tr>
                            <tr>
                                <td>Sex</td>
                                <td>: {{ $fish->sex }}</td>
                            </tr>
                            <tr>
                                <td>DOB</td>
                                <td>: {{ $fish->dob }}</td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td>: {{ $fish->size }}</td>
                            </tr>
                        </table>
                    </h3>

                    <hr class="m-0">

                    <p class="m-0" style="font-size: larger">Note :</p>
                    <p style="font-size: larger">
                        {!! $fish->note !!}
                    </p>

                    <hr class="m-0">

                    <p class="" style="font-size:11px">Harga : <span class="alert-link text-danger"
                            style="font-size:12px">Rp. {{ number_format($fish->harga_ikan, 0, '.', '.') }}</span></p>


                    <hr class="m-0">
                    <a class="btn btn-danger float-end mb-3 mt-3" target="_blank" href="https://wa.me/0811972857" role="button">Question <span><i
                                class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <div class="web">
            <div class="row gx-5">
                <div class="col-3">
                    <div class="m-lg-auto">
                        <img src="{{ $photo }}" class="card-img-top" alt="...">
                        <br><br>
                        <div class="card-body p-0">
                            <a target="_blank" href="{{ $fish->link_video }}" class="btn btn-danger w-100 d-flex justify-content-between"
                                style="font-size:larger">VIDEO <span><i
                                        class="fa-solid fa-circle-chevron-right"></i></span></a>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <p style="font-size: larger">Detail Koi</p>
                    <hr>

                    <div class="row">
                        <div class="col">
                            <h3>
                                <table>
                                    <tr>
                                        <td>Variety</td>
                                        <td>: {{ $fish->variety }}</td>
                                    </tr>
                                    <tr>
                                        <td>Breeder</td>
                                        <td>: {{ $fish->breeder }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bloodline</td>
                                        <td>: {{ $fish->bloodline }}</td>
                                    </tr>
                                </table>
                            </h3>
                        </div>
                        <div class="col">
                            <h3>
                                <table>
                                    <tr>
                                        <td>Sex</td>
                                        <td>: {{ $fish->sex }}</td>
                                    </tr>
                                    <tr>
                                        <td>DOB</td>
                                        <td>: {{ $fish->dob }}</td>
                                    </tr>
                                    <tr>
                                        <td>Size</td>
                                        <td>: {{ $fish->size }}</td>
                                    </tr>
                                </table>
                            </h3>
                        </div>
                    </div>

                    <hr class="m-0">

                    <p class="m-0" style="font-size: larger">Note :</p>
                    <p style="font-size: larger">
                        {!! $fish->note !!}
                    </p>

                    <hr class="m-0">

                    <p style="font-size:30px">Harga : <span class="alert-link text-danger">Rp. {{ number_format($fish->harga_ikan, 0, '.', '.') }}</span></p>
                    <hr>

                    <br><br>
                    <div class="float-end">
                        <a class="btn btn-danger mb-3" target="_blank" href="https://wa.me/0811972857" role="button">Question <span><i
                                    class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <br><br><br><br><br><br>
    </div>
@endsection
