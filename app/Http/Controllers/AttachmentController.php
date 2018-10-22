<?php

namespace App\Http\Controllers;

class AttachmentController extends Controller
{
    /**
     * @param $attachment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($attachment)
    {
        $attachment->delete();
        return back();
    }
}