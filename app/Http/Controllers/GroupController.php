<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Services\Group\GroupService;
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
     * @param GroupService $groupService
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function storeGroup(StoreGroupRequest $request, GroupService $groupService)
    {
        $groupService->addNewGroupToCategory($request);
        return back();
    }

    /**
     * @param Group $group
     * @param UserCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyGroup(Group $group, UserCategory $category)
    {
        $category->groups()->detach($group);
        return back();
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableGroup(Group $group)
    {
        $groupCondition = $group->groupConditions()->
                where('user_id', Auth::id())->get()->first();
        $groupCondition->update([
            'condition' => (int)!$groupCondition->condition
        ]);
        return back();
    }

    /**
     * @param GroupService $groupService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshGroup(GroupService $groupService)
    {
        $groupService->refreshGroupList();
        return back();
    }
}
