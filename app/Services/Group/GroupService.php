<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 03.11.18
 * Time: 19:52
 */

namespace App\Services\Group;

use App\FacebookAccount;
use App\Group;
use App\UserCategory;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public $fb;

    /**
     * GroupService constructor.
     * @param Facebook $fb
     */
    public function __construct(Facebook $fb)
    {
        $this->fb = $fb;
    }


    /**
     * Adding new group to user category by link
     *
     * @param $request
     * @throws FacebookSDKException
     */
    public function addNewGroupToCategory($request)
    {
        $category = UserCategory::find($request->category);
        $groupData = $this->getGroupDataFromFB($request->link);
        $this->updateOrCreateGroup($groupData, $category);
    }

    /**
     *Add moderation group from facebook account
     *
     * @param $facebookAccountId
     * @throws FacebookSDKException
     */
    public function addGroupsFromFbAccount($facebookAccountId)
    {
        $facebookAccount = FacebookAccount::find($facebookAccountId);

        $responsePages = $this->fb->get(
            '/' . $facebookAccount->id . '/accounts',
            $facebookAccount->token
        )->getDecodedBody();

        $responseGroups = $this->fb->get(
            '/me?fields=groups{name,administrator}',
            $facebookAccount->token
        )->getDecodedBody();

        $pages = $responsePages['data'];
        $groups = [];

        foreach ($responseGroups['groups']['data'] as $item){
            if ($item['administrator']){
                array_push($groups, $item);
            }
        }

        $entities = array_merge($groups, $pages);

        array_map(function ($groupData) use (&$facebookAccount) {
            $this->updateOrCreateGroup($groupData, $facebookAccount);
        }, $entities);
    }

    /**
     *Refresh group for all users facebook accounts
     */
    public function refreshGroupList()
    {
        Auth::user()->facebookAccounts->pluck('id')->map(function ($item) {
            $this->addGroupsFromFbAccount($item);
        });
    }

    /**
     * Get group data from facebook group url
     *
     * @param string $link
     * @return mixed
     * @throws FacebookSDKException
     */
    public function getGroupDataFromFB(string $link)
    {
        $facebookAccount = Auth::user()->facebookAccounts->first();

        if (!preg_match('/\d{15,16}/', $link, $output_array)) {
            $output_array = explode('facebook.com/groups/', $link);
            $output_array = explode('/', $output_array[1]);
        };

        $id = $output_array[0];

        $groupData = $this->fb->get(
            '/' . $id,
            $facebookAccount->token
        );

        return json_decode($groupData->getBody(), true);
    }

    /**
     * Update or create new group in DB with relation to instance
     *
     * @param array $groupData
     * @param Model $instance
     */
    public function updateOrCreateGroup(array $groupData, Model $instance)
    {
        $token = $groupData['access_token'] ?? null;
        $groupLink = 'https://www.facebook.com/' . $groupData['id'];

        Group::updateOrCreate(
            [
                'id' => (int)$groupData['id'],
            ], [
                'name' => $groupData['name'],
                'link' => $groupLink,
                'token' => $token,
            ]);
        $instance->user->groups()->syncWithoutDetaching($groupData['id']);
        $instance->groups()->syncWithoutDetaching([$groupData['id']]);
    }
}