<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'link',
        'token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedulePosts()
    {
        return $this->hasMany('App\SchedulePost');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facebookAccounts()
    {
        return $this->belongsToMany('App\FacebookAccount');
    }

    /**
     * @param Group $group
     * @return mixed
     */
    public function busyTime(Group $group)
    {
        $schedulePosts = SchedulePost::where('group_id', '=', $group->id)->pluck('date_to_post');
        return $schedulePosts;
    }
}