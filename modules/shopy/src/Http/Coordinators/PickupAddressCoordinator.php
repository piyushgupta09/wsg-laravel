<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Shopy\Models\PickupAddress;
use Fpaipl\Authy\Http\Resources\AddressResource;

class PickupAddressCoordinator extends Coordinator
{
    public function index()
    {
        return json_encode([
            'success' => true,
            'message' => 'Pickup addresses fetched.',
            'data' => AddressResource::collection(PickupAddress::all()),
        ]);
    }
}