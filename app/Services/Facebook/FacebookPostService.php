<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08.11.18
 * Time: 23:29
 */

namespace App\Services\Facebook;

use App\FacebookAccount;
use App\Group;
use App\UserCategory;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

class FacebookPostService
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


    public function sendFBPost($type_group,
                             $page_id,
                             $text,
                             $images,
                             $videos,
                             $facebookAccountId)
    {

        switch ($type_group) {

            case 'page':
                $token = $this->getPageToken($page_id, $facebookAccountId);


                break;
            case 'group':
                $token = FacebookAccount::find($facebookAccountId)->token;

        }

       if(isset($videos[0]))
       {
           $this->publishVideo($page_id, $text, $videos, $token);
       }
       else {
           $this->publishPost($page_id, $text, $images, $token);
       }
    }



    public function getPageToken($page_id, $facebookAccountId)
    {
        $facebookAccount = FacebookAccount::find($facebookAccountId);
        try {
            $request= $this->fb->get('/' . $page_id . '/?fields=access_token',
                $facebookAccount->token)->getDecodedBody();
            $page_token=$request['access_token'];
            return $page_token;
        } catch (FacebookSDKException $e) {
            info($e);
        }

    }

    public function uploadPhotoToFB($images, $page_id,$token)
    {
        $photoIdArray = array();
        foreach($images as $photoURL) {
            $params = array(
                "url" =>$photoURL,
                "published" => false
            );
            try {
                $postResponse = $this->fb->post('/' . $page_id . '/photos', $params, $token);
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
    public function publishVideo ($page_id,$text, $videos, $token)
    {

        $post = array(
          'description' => $text,
          'source' => $this->fb->videoToUpload(implode($videos))
        );
        try {
            $request = $this->fb->post('/' . $page_id . '/videos',
                $post,
                $token);

            $request = $request->getGraphNode()->asArray();


        } catch (FacebookSDKException $e) {
            info($e);
        }

    }



    public function publishPost($page_id,$text, $images, $token)
    {
        $images_id = $this->uploadPhotoToFB($images,$page_id,$token);

        $post =array('message' => $text);

            foreach ($images_id as $k=>$image) {
                $post['attached_media'][$k] = '{"media_fbid":"' . $image . '"}';
    };
        try {
            $request = $this->fb->post('/' . $page_id . '/feed',
                $post,
                $token);

            $request = $request->getGraphNode()->asArray();
            //info($request);
        } catch (FacebookSDKException $e) {
            info($e);
        }

    }
}