<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Facebook\Facebook;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    public $fb;
    public $facebookLoginService;

    /**
     * LoginController constructor.
     */
    public function __construct(Facebook $fb)
    {
        $this->fb = $fb;
        $this->middleware('guest')->except('logout');
        $this->facebookLoginService = new FacebookLoginService();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->scopes([
            'user_hometown',
            'user_posts',
            'user_age_range',
            'user_likes',
            'user_status',
            'user_link',
            'user_tagged_places',
            'user_friends',
            'user_location',
            'user_videos',
            'user_gender',
            'user_photos',
            'email',
            'user_age_range',
            'user_birthday',
            'user_events',
            'ads_management',
            'pages_manage_cta',
            'pages_show_list',
            'ads_read',
            'pages_manage_instant_articles',
            'publish_pages',
            'business_management',
            'pages_messaging',
            'publish_to_groups',
            'groups_access_member_info',
            'pages_messaging_phone_number',
            'read_page_mailboxes',
            'manage_pages',
            'pages_messaging_subscriptions',
        ])->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderFacebookCallback()
    {
        $authUser = Socialite::driver('facebook')->user();
        $user = $this->facebookLoginService->createOrUpdateUser($authUser);

        Auth::login($user, true);
        return redirect()->to('/');
    }
}