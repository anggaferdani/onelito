<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;

    public function __construct(Request $req)
    {
        $this->request = $req;
    }

    protected function perPage($default = 20)
    {
        $perPage = (int) $this->request->input('per_page');

        // per_page max is 100. for no apparent reason
        return $perPage > 0 && $perPage <= 100 ?
            $perPage : $default;
    }
}
