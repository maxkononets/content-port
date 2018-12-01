<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 19.11.18
 * Time: 12:16
 */

namespace App\Services\Facebook;


use App\Category;
use App\FacebookAccount;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;

class FacebookPostService
{
    public $facebook;
    const COUNT_POSTS_PER_PAGE = 100;

    /**
     * FacebookPostService constructor.
     * @param Facebook $fb
     */
    public function __construct(Facebook $fb)
    {
        $this->facebook = $fb;
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
            $countPostPerRequest = round(self::COUNT_POSTS_PER_PAGE / count($groups));
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

    /**
     * @param $type_group
     * @param $page_id
     * @param $text
     * @param $images
     * @param $videos
     * @param $facebookAccountId
     */
    public function sendFBPost(
        $type_group,
        $page_id,
        $text,
        $images,
        $videos,
        $facebookAccountId
    ){
        switch ($type_group) {

            case 'page':
                $token = $this->getPageToken($page_id, $facebookAccountId);


                break;
            case 'group':
                $token = FacebookAccount::find($facebookAccountId)->token;

        }

        if (isset($videos[0])) {
            $this->publishVideo($page_id, $text, $videos, $token);
        } else {
            $this->publishPost($page_id, $text, $images, $token);
        }
    }


    public function getPageToken($page_id, $facebookAccountId)
    {
        $facebookAccount = FacebookAccount::find($facebookAccountId);
        try {
            $request = $this->facebook->get('/' . $page_id . '/?fields=access_token',
                $facebookAccount->token)->getDecodedBody();
            $page_token = $request['access_token'];
            return $page_token;
        } catch (FacebookSDKException $e) {
            info($e);
        }

    }

    public function uploadPhotoToFB($images, $page_id, $token)
    {
        $photoIdArray = array();
        foreach ($images as $photoURL) {
            $params = array(
                "url" => $photoURL,
                "published" => false
            );
            try {
                $postResponse = $this->facebook->post('/' . $page_id . '/photos', $params, $token);
                $photoId = $postResponse->getDecodedBody();
                if (!empty($photoId["id"])) {
                    $photoIdArray[] = $photoId["id"];
                }
            } catch (FacebookResponseException $e) {
                exit();
            } catch (FacebookSDKException $e) {
                exit();
            }
        }
        return $photoIdArray;

    }

    public function publishVideo($page_id, $text, $videos, $token)
    {

        $post = array(
            'description' => $text,
            'source' => $this->facebook->videoToUpload(implode($videos))
        );
        try {
            $request = $this->facebook->post('/' . $page_id . '/videos',
                $post,
                $token);

            $request = $request->getGraphNode()->asArray();
        } catch (FacebookSDKException $e) {
            info($e);
        }

    }


    public function publishPost($page_id, $text, $images, $token)
    {
        $images_id = $this->uploadPhotoToFB($images, $page_id, $token);

        $post = array('message' => $text);

        foreach ($images_id as $k => $image) {
            $post['attached_media'][$k] = '{"media_fbid":"' . $image . '"}';
        };
        try {
            $request = $this->facebook->post('/' . $page_id . '/feed',
                $post,
                $token);

            $request = $request->getGraphNode()->asArray();
            //info($request);
        } catch (FacebookSDKException $e) {
            info($e);
        }

    }
}
