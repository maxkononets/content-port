<?php

namespace App\Jobs;

use App\Group;
use App\Image;
use App\SchedulePost;
use App\Services\Facebook\FacebookPostService;
use App\Video;
use Facebook\Facebook;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Request;

 class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $groupId;
    protected $facebookId;
    protected $text;
    protected $images;
    protected $videos;
    protected $type_group;
    protected $post;

    /**
     * Create a new job instance.
     *
     * @param SchedulePost $schedulePost
     */
    public function __construct(SchedulePost $schedulePost)
    {
        $this->groupId = $schedulePost->group_id;
        $this->facebookId = Group::find($schedulePost->group_id)->facebookAccounts->first()->id;
        $this->text = $schedulePost->text;
        $this->images = $schedulePost->attachments()
            ->where('entity_type', Image::class)->get()->map(function ($attachment) {
                return Request::root() . $attachment->entity->route;
           })->toArray();
        $this->videos = $schedulePost->attachments()
            ->where('entity_type', Video::class)->get()->map(function ($attachment) {
                return Request::root() . $attachment->entity->route;
            })->toArray();

        $this->type_group = Group::select()->where('id', '=', $this->groupId)->value('type');
        $this->post = $schedulePost;
    }


    public function handle(FacebookPostService $postService)
    {


        $postService -> sendFBPost(
            $this->type_group,
            $this->groupId,
            $this->text,
            $this->images,
            $this->videos,
            $this->facebookId);
        $this->post->delete();
    }
}
