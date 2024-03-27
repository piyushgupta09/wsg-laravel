<?php

namespace Fpaipl\Panel\Http\Coordinators;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Coordinator extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function reqHasApiSecret($request){
        $viar = false;
        if($request->has('api_secret') && env('API_SECRET') == $request->get('api_secret') ){
            $viar = true;
        }
        return $viar;
    }
}
