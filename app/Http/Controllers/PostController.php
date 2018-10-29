<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\SchedulePostRequest;
use App\SchedulePost;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * @param SchedulePostRequest $request
     */
    public function schedule(SchedulePostRequest $request)
    {
        $shedulePost = new SchedulePost();
        $shedulePost->fill($request->all());
        $shedulePost->save();
        return redirect()->back();

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newPost()
    {
        $adminGroups = Auth::user()->adminGroups();
        return view('newpost', [
            'admin_groups' => $adminGroups,
        ]);
    }

    /**
     * @param Group $group
     * @return mixed
     */
    public function busyTime(Group $group)
    {
        $schedulePosts = SchedulePost::where('group_id', '=', $group->id)->pluck('date_to_post');
        return $schedulePosts;
    }
}