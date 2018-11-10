<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\UserCategory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facebookAccounts()
    {
        return $this->hasMany('App\FacebookAccount');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'group_conditions', 'user_id', 'group_id');
    }

    /**
     * @param bool $condition
     * @return array
     */
    public function adminGroups(bool $condition = false)
    {
        $groups = [];
        $accounts = $this->facebookAccounts;
        foreach ($accounts as $account) {
            $accountGroups = $condition ?
                $account->groups()->
                        join('group_conditions', 'groups.id', '=', 'group_conditions.group_id')->
                        select('groups.*', 'group_conditions.condition')->
                        where('condition', '=', $condition)->
                        get() :
                $account->groups;
            foreach ($accountGroups as $group) {
                    array_push($groups, $group);
            }
        }
        return $groups;
    }
}
