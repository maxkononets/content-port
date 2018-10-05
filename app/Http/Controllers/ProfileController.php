<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile/profile',[
            'user' => $user,
        ]);
    }

    public function secure()
    {
        $user = Auth::user();
        return view('profile/secure',[
            'email' => $user->email,
        ]);
    }

}
