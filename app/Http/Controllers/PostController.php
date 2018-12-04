<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Group;
use App\Http\Requests\SchedulePostRequest;
use App\Image;
use App\SchedulePost;
use App\Services\Post\SchedulePostService;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use MongoDB\Driver\Exception\WriteException;

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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(schedulePost $post, $country, $city)
    {
        $post->publication_time = new Carbon($post->publication_time);
//        $post->publication_time->setTimezone($country . '/' . $city);
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function storeAttachments(Request $request)
    {
        return Attachment::store($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAttachmentsFromLinks(Request $request)
    {
        $data = json_decode($request->all()['attachments'], true);
        $response = Attachment::storeFromLinks($data);
        return response()->json($response, 200);
    }

    /**
     * Paginate attachments on entity type
     *
     * @param $entity
     * @return mixed
     */
    public function paginateOfAttachmentEntity($entity)
    {
        switch ($entity) {
            case 'image':
                $attachments = Auth::user()->attachments()->where('entity_type', Image::class)->paginate(16);
                break;
            case 'video';
                $attachments = Auth::user()->attachments()->where('entity_type', Video::class)->paginate(16);
                break;
        }
        $attachmentsEntity = $attachments->map(function ($item){
            return $item->entity;
        });

        $attachmentsEntity['next'] = $attachments->nextPageUrl();

        return $attachmentsEntity->toJson();
    }
}