<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Group;
use App\Http\Requests\SchedulePostRequest;
use App\SchedulePost;
use App\Services\Post\SchedulePostService;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newPost()
    {
        $adminGroups = Auth::user()->adminGroups(true);
        $gallery = Attachment::getOwnerAttachments(Auth::user());

        return view('newpost', [
            'admin_groups' => $adminGroups,
            'gallery' => $gallery,
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
        $attachments = Attachment::getOwnerAttachments($post);
        $gallery = Attachment::getOwnerAttachments(Auth::user());
        $adminGroups = Auth::user()->adminGroups(true);
        return view('schedule.update', [
                'post' => $post,
                'admin_groups' => $adminGroups,
            ] + $attachments + [
                'gallery' => $gallery,
                ]
        );
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
     * Edit post
     *
     * @param SchedulePost $schedulePost
     * @param SchedulePostRequest $request
     * @param SchedulePostService $postService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPost(SchedulePost $schedulePost, SchedulePostRequest $request, SchedulePostService $postService)
    {
        $post = $postService->store($request, $schedulePost);
        return $this->showScheduledPostsGroup(Group::find($post->group_id));
    }

    /**
     * Create new post
     *
     * @param SchedulePostRequest $request
     * @param SchedulePostService $postService
     * @param SchedulePost $schedulePost
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSchedulePost(SchedulePostRequest $request, SchedulePostService $postService, SchedulePost $schedulePost)
    {
        $postService->store($request, $schedulePost);
        return back();
    }
}