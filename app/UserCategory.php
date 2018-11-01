<?php

namespace App;

use App\Providers\Categoriable;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model implements Categoriable
{
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
}
