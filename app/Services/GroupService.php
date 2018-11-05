<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 03.11.18
 * Time: 19:52
 */

namespace App\Services;

use App\FacebookAccount;
use App\Group;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public $fb;

    /**
     * GroupService constructor.
     */
    public function __construct()
    {
        try {
            $config = config('services.facebook');
            $this->fb = new Facebook([
                'app_id' => $config['client_id'],
                'app_secret' => $config['client_secret'],
                'default_graph_version' => 'v3.1',
            ]);
        } catch (FacebookSDKException $e) {
            dump($e);
        }
    }

    /**
     * @param $facebookAccountId
     * @throws FacebookSDKException
     */
    public function addGroupsFromFbAccount($facebookAccountId)
    {
        $facebookAccount = FacebookAccount::find($facebookAccountId);

        $response = $this->fb->get(
            '/' . $facebookAccount->id . '/accounts',
            $facebookAccount->token
        );

        $groups = collect(json_decode($response->getBody(), true)['data']);

        $groups->map(function ($groupData) use (&$facebookAccount) {
            Group::updateOrCreate(
                [
                    'id' => (int)$groupData['id']
                ], [
                    'name' => $groupData['name'],
                    'token' => $groupData['access_token'],
                    'link' => 'https://www.facebook.com/' . $groupData['id'],
                ]
            );
            $facebookAccount->groups()->syncWithoutDetaching([$groupData['id']]);
        });
    }

    /**
     *
     */
    public function refreshGroupList()
    {
        Auth::user()->facebookAccounts->pluck('id')->map(function ($item) {
            $this->addGroupsFromFbAccount($item);
        });
    }
}