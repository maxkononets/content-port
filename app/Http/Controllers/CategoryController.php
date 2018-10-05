<?php

namespace App\Http\Controllers;

use App\CustomCategory;
use App\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchContent()
    {
        $userCategory = Auth::user()->categories;
        $customCategory = CustomCategory::all();
        return view('search', [
            'user_category' => $userCategory,
            'custom_category' => $customCategory,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('category.store');
    }

    /**
     * @param Request $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $name)
    {
        $category = new UserCategory();
        $category->fill($name->all() + [
            'user_id' => Auth::id(),
                ]);
        $category->save();

        return view('category.store');
    }
}
