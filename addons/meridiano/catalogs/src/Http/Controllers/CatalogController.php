<?php

namespace Meridiano\Catalogs\Http\Controllers;

use Statamic\Http\Controllers\Controller;

class CatalogController extends Controller
{
    public function index()
    {
        $this->authorize('view catalogo');

        return view('catalog::index');
    }
}
