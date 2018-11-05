<?php

namespace App;

use App\Providers\Categoriable;
use Illuminate\Database\Eloquent\Model;

class CustomCategory extends Model implements Categoriable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
}
