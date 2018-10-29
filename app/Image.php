<?php

namespace App;

class Image extends AttachmentFile
{
    protected $fillable = [
        'name',
        'size',
        'route',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'entity');
    }
}
