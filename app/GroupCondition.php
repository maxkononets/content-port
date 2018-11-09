<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupCondition extends Model
{
    protected $primaryKey = 'group_id';

    protected $fillable = [
        'condition',
        'group_id',
        'user_id',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
