<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Group;
use App\Http\Requests\SchedulePostRequest;
use App\SchedulePost;
use App\Services\Post\SchedulePostService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Facebook\FacebookPostService;


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
    public function update(schedulePost $post, Request $request)
    {
        $post->publication_time = new Carbon($post->publication_time);
        $post->publication_time->setTimezone($request->timezone);
        $post->date = $post->publication_time->toDateString();
        $post->time = $post->publication_time->toTimeString();
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

    public function sendPost(FacebookPostService $postService)
    {
        $type_group= 'page';
        $page_id = '2375351699408980';
        $post = [
            'message' => 'Теперь тут снова котики',
            'link' => 'https://i.ytimg.com/vi/JaciHAcvlyA/hqdefault.jpg'
        ];
        $facebookAccountId='2382322111841676';


                $postService->publishToPages($page_id, $post, $facebookAccountId);
                dd($postService);

    }
}