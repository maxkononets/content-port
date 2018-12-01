<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\CustomCategory;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\Facebook\FacebookPostService;
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
     * @param StoreCategoryRequest $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeCategory(StoreCategoryRequest $name)
    {
        $category = new UserCategory();
        $category->fill($name->all() + [
                'user_id' => Auth::id(),
            ]);
        $category->save();
        return back();
    }

    /**
     * @param UserCategory $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(UserCategory $category)
    {
        $category->groups()->detach();
        $category->delete();
        return back();
    }

    public function showCategory(UserCategory $category, FacebookPostService $facebookPostService)
    {
        $adminGroups = Auth::user()->adminGroups(true);
        $groups = $category->groups;
        $gallery = Attachment::getOwnerAttachments(Auth::user());
        return view('category.category', [
            'category' => $category,
            'groups' => $groups,
            'admin_groups' => $adminGroups,
            'gallery' => $gallery,
        ]);
    }

    public function showCustomCategory(CustomCategory $category, FacebookPostService $facebookPostService)
    {
        return view('category.category', [
            'category' => $category,
        ]);
    }

    public function getPostsCustomCategoryJson(CustomCategory $category, FacebookPostService $facebookPostService){
        echo json_encode($facebookPostService->getPosts($category), true);
    }

    public function getPostsUserCategoryJson(UserCategory $category, FacebookPostService $facebookPostService){
        echo json_encode($facebookPostService->getPosts($category), true);
    }
}