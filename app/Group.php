<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'id',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupConditions()
    {
        return $this->hasMany('App\GroupCondition');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'group_conditions', 'group_id', 'user_id');
    }
}