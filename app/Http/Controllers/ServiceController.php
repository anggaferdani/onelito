<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function cities()
    {
        $provId = $this->request->input('prov_id');

        $cities = Province::findOrFail($provId)->cities;

        return response()->json($cities);
    }

    public function districts()
    {
        $cityId = $this->request->input('city_id');

        $districts = City::findOrFail($cityId)->districts;

        return response()->json($districts);
    }

    public function subdistricts()
    {
        $disId = $this->request->input('dis_id');

        $subdistricts = District::findOrFail($disId)->subdistricts;

        return response()->json($subdistricts);
    }
}
