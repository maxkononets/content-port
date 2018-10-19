<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name',
        'size',
        'route',
    ];

    public static function store($request, SchedulePost $post)
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $name = $attachment->getClientOriginalName();
                $size = $attachment->getSize();
                $route = $attachment->store('attachments/image');
                $obj = new Attachment();
                $obj->fill([
                    'name' => $name,
                    'size' => $size,
                    'route' => $route,
                ]);
                $post->attachments()->save($obj);
            }
        }
        return;
    }
}