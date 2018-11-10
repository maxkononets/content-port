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
use App\Jobs\PublishPost;
use App\SchedulePost;

class SchedulePostService
{
    /**
     * store created or updated post
     *
     * @param $postFields
     * @param SchedulePost $post
     * @return SchedulePost
     */
    public function store($postFields, SchedulePost $post)
    {
        $post->fill($postFields->all())->save();
        Group::find($postFields->group_id)->schedulePosts()->save($post);

        Attachment::store($postFields, $post);

        $this->makeJob($post);
        return $post;
    }

    /**
     * add PublishPost job
     *
     * @param SchedulePost $post
     */
    public function makeJob(SchedulePost $post)
    {
        if (!($post->date && $post->time)) {
            $time = now();
        } else {
            $time = strtotime($post->date . '' . $post->time);
        }

        PublishPost::dispatch($post)->delay($time);
        return;
    }
}