<?php

namespace Fpaipl\Authy\Http\Controllers;

use Fpaipl\Authy\Datatables\NewAccountDatatable as Datatable;
use Fpaipl\Panel\Http\Controllers\PanelController;

class NewAccountController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Authy\Models\Account' , 
            'account', 
            'accounts.index'
        );
    }
}
