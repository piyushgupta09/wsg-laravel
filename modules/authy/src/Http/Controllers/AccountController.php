<?php

namespace Fpaipl\Authy\Http\Controllers;

use Fpaipl\Authy\Datatables\AccountDatatable as Datatable;
use Fpaipl\Panel\Http\Controllers\PanelController;

class AccountController extends PanelController
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
