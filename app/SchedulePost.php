<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulePost extends Model
{
    protected $fillable = [
        'text',
        'date',
        'time',
        'group_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }
}
