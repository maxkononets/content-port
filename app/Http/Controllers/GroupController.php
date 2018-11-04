<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Services\GroupService;
use App\UserCategory;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myGroups()
    {
        $personalPages = Auth::user()->facebookAccounts;
        $adminGroups = Auth::user()->adminGroups();
        return view('mygroups', [
            'personal_pages' => $personalPages,
            'admin_groups' => $adminGroups,
        ]);
    }

    /**
     * @param StoreGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeGroup(StoreGroupRequest $request)
    {
        $category = UserCategory::find($request->category);
        $group = Group::where('link', $request->link)->first();

        if (!$group) {
            $category->groups()->create($request->all() + [
                    'name' => 'defaultname',
                ]);
        }

        $category->groups()->attach($group);
        return back();
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroyGroup(Group $group)
    {
        $group->delete();
        return back();
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableGroup(Group $group)
    {
        $group->condition = (int)!$group->condition;
        $group->save();
        return back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshGroup()
    {
        $groupService = new GroupService();
        $groupService->refreshGroupList();
        return back();
    }
}
