<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Fpaipl\Panel\Http\Coordinators\Coordinator;

class BankCoordinator extends Coordinator
{
    
    public function index()
    {
        return json_encode([
            'transfer' => config('settings.transfer'),
            'upipay' => config('settings.upipay'),
            'modes' => config('settings.pay_modes'),
        ]);
    }
}
