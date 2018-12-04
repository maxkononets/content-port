<?php

namespace App;

use App\Providers\Categoriable;
use App\Category;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Category implements Categoriable
{
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
