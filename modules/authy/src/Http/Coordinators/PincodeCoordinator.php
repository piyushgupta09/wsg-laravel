<?php

namespace Fpaipl\Authy\Http\Coordinators;

use League\Glide\Api\Api;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fpaipl\Authy\Models\Pincode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;

class PincodeCoordinator extends Coordinator
{
    /**
     * Display the specified resource.
     */
    public function show(Pincode $pincode)
    {
        Cache::forget('pincodes'.$pincode->id);
        $pincode = Cache::remember('pincodes'.$pincode->id, 24 * 60 * 60, function () use($pincode) {
            return Pincode::with('district')->where('id',$pincode->id)->first();
        });
        
        return [
            'id' => $pincode->id,
            'pincode' => $pincode->pincode,
            'district' => [
                'id' => $pincode->district->id,
                'name' => $pincode->district->name,
            ],
            'state' => [
                'id' => $pincode->district->state->id,
                'name' => $pincode->district->state->name,
            ],
            'country' => [
                'id' => $pincode->district->state->country->id,
                'name' => $pincode->district->state->country->name,
            ],
        ];
    }

    public function validatePincode(Request $request)
    {
        $pincode = $request->pincode;
        $pincode = Pincode::where('pincode', $pincode)->first();
        if($pincode){
            return ApiResponse::success([
                'pincode' => $pincode->pincode,
                'district_id' => $pincode->district->id,
                'district' => $pincode->district->name,
                'state' => $pincode->district->state->name,
                'country' => $pincode->district->state->country->name,
                'dsc' => Str::title($pincode->district->name . ', ' . $pincode->district->state->name . ', ' . $pincode->district->state->country->name),
            ], 'Pincode is valid.');
        }
    }
}
