<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Group;
use App\Http\Requests\SchedulePostRequest;
use App\SchedulePost;
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
     * @param SchedulePostRequest $request
     * @param SchedulePost $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPost(SchedulePostRequest $request, schedulePost $post)
    {
        $post->fill($request->all());
        Group::find($request->group_id)->schedulePosts()->save($post);

        Attachment::store($request, $post);

        $post->save();
        return $this->showScheduledPostsGroup(Group::find($post->group_id));
    }

    /**
     * @param SchedulePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSchedulePost(SchedulePostRequest $request)
    {
        $post = new SchedulePost();
        $post->fill($request->all());
        Group::find($request->group_id)->schedulePosts()->save($post);

        Attachment::store($request, $post);

        $post->save();
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