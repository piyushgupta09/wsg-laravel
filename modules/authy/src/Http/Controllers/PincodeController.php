<?php

namespace Fpaipl\Authy\Http\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Fpaipl\Authy\Imports\PincodeImport;

class PincodeController extends Controller
{
    public function import(){
        ini_set('max_execution_time',3600);
        Excel::import(new PincodeImport, storage_path('pincode.csv'));
    }
}
