<?php

namespace App\Http\Controllers;

use App\Providers\Categoriable;

class SearchController extends Controller
{
    public function Search(Categoriable $category, string $phrase)
    {
        //TODO: here must be call search method
    }

    public function GlobalSearch(string $phrase)
    {
        //TODO: here must be call search method
    }
}
