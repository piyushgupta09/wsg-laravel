<?php

namespace Fpaipl\Prody\Http\Controllers;

use Fpaipl\Prody\Actions\SyncUnits;
use App\Http\Controllers\Controller;
use Fpaipl\Prody\Actions\SyncMaterials;

class SyncController extends Controller
{
    public function all()
    {
        // SyncUnits::execute();
        // SyncMaterials::execute();
        
        // return redirect()->back()->with('toast', [
        //     'class' => 'success',
        //     'text' => 'Synced successfully.'
        // ]);
    }
}
