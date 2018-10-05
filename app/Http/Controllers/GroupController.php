<?php

namespace App\Http\Controllers;

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
}
