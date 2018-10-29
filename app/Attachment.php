<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Attachment extends Model
{
    protected $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schedulePosts()
    {
        return $this->belongsToMany('App\SchedulePost');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * @param Model $owner
     * @return array
     */
    public static function getOwnerAttachments(Model $owner)
    {
        $attach = [
            'images' => [],
            'videos' => [],
        ];

        $attachments = $owner->attachments()->get();
        $attachments->map(function ($attachment) use (&$attach) {
            if ($attachment->entity instanceof Image) {
                array_push($attach['images'], $attachment->entity);
            }
            if ($attachment->entity instanceof Video) {
                array_push($attach['videos'], $attachment->entity);
            }
        });
        return $attach;
    }

    public static function store($request, SchedulePost $post)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $objAttachment = new self();
                $objImage = Image::store($image);
                Auth::user()->attachments()->save($objAttachment);
                $objImage->attachments()->save($objAttachment);
                $post->attachments()->attach($objAttachment);
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $objAttachment = new self();
                $objVideo = Video::store($video);
                Auth::user()->attachments()->save($objAttachment);
                $objVideo->attachments()->save($objAttachment);
                $post->attachments()->attach($objAttachment);
            }
        }
        return;
    }
}