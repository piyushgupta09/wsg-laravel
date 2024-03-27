<?php

namespace Fpaipl\Panel\Http\Controllers;

use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function actionSearch()
    {
        return view('panel::pages.search-results');
    }
}
