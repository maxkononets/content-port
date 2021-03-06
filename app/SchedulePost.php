<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulePost extends Model
{
    protected $fillable = [
        'text',
        'publication_time',
        'group_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attachments()
    {
        return $this->belongsToMany('App\Attachment');
    }
}