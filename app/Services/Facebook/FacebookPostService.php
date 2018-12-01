<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 19.11.18
 * Time: 12:16
 */

namespace App\Services\Facebook;


use App\Category;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;

class FacebookPostService
{
    public $facebook;

    /**
     * FacebookPostService constructor.
     * @param Facebook $fb
     */
    public function __construct(Facebook $fb)
    {
        $this->facebook = $fb;
        define('COUNT_POSTS_PER_PAGE', 100);
    }

    /**
     * return facebook posts
     *
     * @param Category $category
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPosts(Category $category)
    {
        $groups = $category->groups;
        if($groups->isNotEmpty()) {
            $fbAccount = Auth::user()->facebookAccounts->first();
            $countPostPerRequest = round(COUNT_POSTS_PER_PAGE / count($groups));
            $groupsIds = $groups->pluck('id');
            $batchRequests = $groupsIds->map(function ($item) use ($fbAccount, $countPostPerRequest) {
                return $this->facebook->request(
                    'get',
                    '/' . $item . '?fields=feed.limit(' . $countPostPerRequest . '){attachments{subattachments,media},message,source}'
                );
            });

            $response = $this->facebook->sendBatchRequest($batchRequests->toArray(), $fbAccount->token);

            return $this->preparePostsDataStructure($response);
        }
        return ['error' => "Category sources is empty. Please, add groups to category and all be OK :)"];
    }

    /**
     * preparing posts data structure
     *
     * @param $postData
     * @return array
     */
    protected function preparePostsDataStructure($postData)
    {
        $postsResponse = collect($postData->getDecodedBody())->pluck('body')->map(function ($item){
            return json_decode($item, true)['feed']['data'];
        })->collapse()->toArray();
        $posts = $this->pluckPostData($postsResponse);
//
//        $paging = collect($postData->getDecodedBody())->pluck('body')->map(function ($item){
//            return json_decode($item, true)['feed']['paging'];
//        })->toArray();
        return [
            'posts' => $posts,
//            'paging' => $paging,
        ];
    }

    /**
     * Pluck response posts data from facebook to simple array of posts
     *
     * @param $postData
     * @return array
     */
    protected function pluckPostData($postData)
    {
        $prepareData = array_map(function ($item) {
            $result['link'] = 'https://facebook.com/' . $item['id'];
            $result['message'] = $item['message'] ?? '';

            if (isset($item['source'])) {
                $result['attachments']['videos'][] = $item['source'];
            }

            $attachment = $item['attachments']['data'][0]['subattachments']['data']
                ?? $item['attachments']['data']
                ?? [];

            $result['attachments']['images'] = array_map(function ($attachment) {
                return $attachment['media']['image']['src'];
            }, $attachment);

            return $result;
        }, $postData);
        return $prepareData;
    }

}