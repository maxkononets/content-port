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
     * @return \Illuminate\Http\RedirectResponse
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showScheduledPosts(){
        $adminGroups = Auth::user()->adminGroups();
        return view('schedule.groups',[
            'groups' => $adminGroups,
        ]);
    }

    /**
     * @param Group $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showScheduledPostsGroup(Group $group)
    {
        $posts = $group->schedulePosts;
        return view('schedule.posts', [
            'posts' => $posts,
            'group' => $group,
        ]);
    }

    /**
     * @param SchedulePost $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(schedulePost $post)
    {
        $attachments = $post->attachments;
        return view('schedule.update', [
            'post' => $post,
            'attachments' => $attachments,
        ]);
    }

    /**
     * @param SchedulePost $post
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroyPost(schedulePost $post)
    {
        $post->delete();
        return back();
    }

    /**
     * @param SchedulePostRequest $request
     * @param SchedulePost $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPost(SchedulePostRequest $request, schedulePost $post)
    {
        $post->fill($request->all());
        $post->save();
        return $this->showScheduledPostsGroup(Group::find($post->group_id));
    }
}