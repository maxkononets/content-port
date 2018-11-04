<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 03.11.18
 * Time: 19:06
 */

namespace App\Services;

use App\User;

class FacebookLoginService
{

    /**
     * @param $authUser
     * @return User
     */
    public function createOrUpdateUser($authUser): User
    {
        $user = User::updateOrCreate(
            [
                'email' => $authUser->email
            ],
            [
                'name' => $authUser->name,
                'password' => bcrypt(str_random(20))
            ]);

        return $this->createOrUpdateFbAccount($user, $authUser);
    }

    /**
     * @param User $user
     * @param $fbAccountData
     * @return User
     */
    public function createOrUpdateFbAccount(User $user, $fbAccountData): User
    {
        $user->facebookAccounts()->updateOrCreate(
            [
                'id' => (int)$fbAccountData->id
            ],
            [
                'name' => $fbAccountData->name,
                'link' => $fbAccountData->profileUrl,
                'token' => $fbAccountData->token,
            ]);

        $groupService = new GroupService();
        $groupService->addGroupsFromFbAccount($fbAccountData->id);

        return $user;
    }
}
