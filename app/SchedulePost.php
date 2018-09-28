<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulePost extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }
}
