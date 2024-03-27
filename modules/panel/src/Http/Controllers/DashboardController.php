<?php

namespace Fpaipl\Panel\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $labels = ['Red', 'Blue', 'Yellow'];
        $data = [12, 50, 3];

        return view('panel::dashboard', compact('labels', 'data'));
    }
}
