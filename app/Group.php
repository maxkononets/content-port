<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'link',
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
}
