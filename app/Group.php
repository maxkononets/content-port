<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedulePosts()
    {
        return $this->hasMany('App\ScedulePost');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facebookAccounts()
    {
        return $this->belongsToMany('App\FacebookAccount');
    }
}
