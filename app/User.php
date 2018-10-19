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
     * @param bool $condition
     * @return array
     */
    public function adminGroups(bool $condition = false)
    {
        $groups = [];
        $accounts = FacebookAccount::all()->where('user_id', '=', $this->id);
        foreach ($accounts as $account) {
            $groupsAccount = $condition ? $account->groups()->where('condition', $condition)->get() : $account->groups;
            foreach ($groupsAccount as $group) {
                    array_push($groups, $group);
            }
        }
        return $groups;
    }
}
