<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 09.11.18
 * Time: 15:33
 */

namespace App\Services\Post;

use App\Attachment;
use App\Group;
use App\SchedulePost;
use Carbon\Carbon;

class SchedulePostService
{
    /**
     * store created or updated post
     *
     * @param $request
     * @param SchedulePost $post
     * @return SchedulePost
     */
    public function store($request, SchedulePost $post)
    {
        $postFields = $this->prepareTime($request);
        $post->fill($postFields->all())->save();
        Group::find($postFields['group_id'])->schedulePosts()->save($post);
        Attachment::saveOnIds($post, [
            'images' => $postFields['images'],
            'videos' => $postFields['videos'],
        ]);
//        Attachment::store($postFields, $post);
        return $post;
    }

    /**
     * finds the difference in user time and publication time and assigns the publication date to the time zone of the server
     *
     * @param $requestFields
     * @return mixed
     */
    protected function prepareTime($requestFields)
    {
        $userTime = new Carbon('', $requestFields['timezone']);

        if (is_null($requestFields['time']) || is_null($requestFields['date'])) {
            $requestFields['publication_time'] = Carbon::now();
        } else {
            $userTimeToPost = new Carbon($requestFields['date'] . ' ' . $requestFields['time'], $requestFields['timezone']);
            $diffTimeToPost = $userTime->diffInSeconds($userTimeToPost);
            $timeToPost = new Carbon();
            $timeToPost->addSeconds($diffTimeToPost);
            $requestFields['publication_time'] = $timeToPost;
        }
        return $requestFields;
    }
}