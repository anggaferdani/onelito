@extends('layout.main')

@section('container')
    <style>
        .cb-judul {
            height: 4rem;

        }

        .grid {
            display: grid;
            grid-template-columns: auto auto auto auto;
            gap: 10px;
            justify-content: center;
        }

        
        .grid > div img {
        width: 100%;
        grid-area: 1/1/2/2;
        aspect-ratio: 1/1;
        }

        @media screen and (max-width:982px) {
            .grid {
                grid-template-columns: auto auto auto;
            }
        }

        @media screen and (max-width:764px) {
            .grid {
                grid-template-columns: auto auto;
            }
        }

        @media screen and (max-width:600px) {
            .grid {
                grid-template-columns: auto;
                padding-left: 5%;
                padding-right: 5%;
            }
        }
    </style>
    <br><br><br><br><br>
    <div class="container grid center">
        <!-- <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3 mb-5"> -->
        <!-- <div class="row row-cols-1 row-cols-lg-5 g-2 g-lg-3 mb-5"> -->
            @forelse ($championFishes as $fish)
                @php
                    $photoChampion = 'img/koi12.jpg';

                    if ($fish->foto_ikan !== null) {
                        $photoChampion = url('storage') . '/' . $fish->foto_ikan;
                    }
                @endphp
                <div class="d-grid">
                        <img src="{{ $photoChampion }}" class="" alt="...">
                </div>
            @empty
            @endforelse
        <!-- </div> -->
    </div>
    <br><br>
@endsection
