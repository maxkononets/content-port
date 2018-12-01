<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 19.11.18
 * Time: 12:22
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

abstract class Category extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
}