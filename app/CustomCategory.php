<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomCategory extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
}
