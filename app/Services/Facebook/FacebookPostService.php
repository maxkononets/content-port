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


    public function sendPost($type_group, $page_id, $post, $facebookAccountId)
    {

        switch ($type_group) {

            case 'page':
                $token = $this->getPageToken($page_id, $facebookAccountId);
                $endpoint='accounts';

                break;
            case 'group':
                $token = FacebookAccount::find($facebookAccountId)->token;
                $endpoint='feed';
        }

        if(isset($post->videos))
        {
            $endpoint = 'videos';
        }

        $this->publishPost($page_id,$endpoint,$post,$token);

    }

    public function publishToGroup($page_id, $post, $facebookAccountId)
    {
        $facebookAccount = FacebookAccount::find($facebookAccountId);

       // dd($facebookAccount->token);
        try {
            $request = $this->fb->post('/' . $page_id . '/feed',
                $post,
                $facebookAccount->token);

            $request = $request->getGraphNode()->asArray();

          dd ($request);

        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function getPageToken($page_id, $facebookAccountId)
    {
        $facebookAccount = FacebookAccount::find($facebookAccountId);
        try {
            $request= $this->fb->get('/' . $page_id . '/?fields=access_token',
                $facebookAccount->token)->getDecodedBody();
            $page_token=$request['access_token'];
            Return $page_token;
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }

    }


    public function publishToPages($page_id, $post, $facebookAccountId)
    {

        try {
            $request = $this->fb->post('/' . $page_id . '/accounts',
                $post,
                $this->getPageToken($page_id, $facebookAccountId));

            $request = $request->getGraphNode()->asArray();
            dd ($request);

        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }

    }

    public function publishVideo ()
    {
        try {
            $request = $this->fb->post('/' . $page_id . '/videos',
                $post,
                $token);

            $request = $request->getGraphNode()->asArray();
            dd ($request);

        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }

    }

    public function publishPost($page_id, $endpoint, $post, $token)
    {
        try {
            $request = $this->fb->post('/' . $page_id . '/' . $endpoint,
                $post,
                $token);

            $request = $request->getGraphNode()->asArray();
            dd ($request);

        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }

    }

}