<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 03.11.18
 * Time: 19:06
 */

namespace App\Services\Facebook;

use App\Services\Group\GroupService;
use App\User;

class FacebookLoginService
{
    public $groupService;

    /**
     * FacebookLoginService constructor.
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @param $authUser
     * @return User
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function createOrUpdateUser($authUser): User
    {
        $user = User::updateOrCreate(
            [
                'email' => $authUser->email,
            ],
            [
                'name' => $authUser->name,
                'password' => bcrypt(str_random(20)),
            ]);

        return $this->createOrUpdateFbAccount($user, $authUser);
    }

    /**
     * @param User $user
     * @param $fbAccountData
     * @return User
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function createOrUpdateFbAccount(User $user, $fbAccountData): User
    {
        $user->facebookAccounts()->updateOrCreate(
            [
                'id' => (int)$fbAccountData->id,
            ],
            [
                'name' => $fbAccountData->name,
                'link' => $fbAccountData->profileUrl,
                'token' => $fbAccountData->token,
            ]);

        $this->groupService->addGroupsFromFbAccount($fbAccountData->id);

        return $user;
    }
}
